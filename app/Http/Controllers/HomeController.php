<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        // Daily metrics (Today only)
        $today = today();
        $totalPatients = \App\Models\Patient::whereDate('created_at', $today)->count();
        $totalCompleted = \App\Models\TestReport::whereDate('created_at', $today)->count();
        $totalAppointments = \App\Models\Appointment::whereDate('created_at', $today)->count();
        $pendingReports = max(0, $totalAppointments - $totalCompleted);

        return view('dashboard', compact('totalPatients', 'totalCompleted', 'totalAppointments', 'pendingReports'));
    }

    public function getDashboardStats(\Illuminate\Http\Request $request)
    {
        $today = today();
        $totalPatients = \App\Models\Patient::whereDate('created_at', $today)->count();
        $totalCompleted = \App\Models\TestReport::whereDate('created_at', $today)->count();
        $totalAppointments = \App\Models\Appointment::whereDate('created_at', $today)->count();
        $pendingReports = max(0, $totalAppointments - $totalCompleted);

        return response()->json([
            'totalPatients' => $totalPatients,
            'totalCompleted' => $totalCompleted,
            'totalAppointments' => $totalAppointments,
            'pendingReports' => $pendingReports,
        ]);
    }

    public function testManagement()
    {
        $tests = \App\Models\LabTest::latest()->get();
        return view('tests', compact('tests'));
    }

    public function appointments(\Illuminate\Http\Request $request)
    {
        $appointments = \App\Models\Appointment::with(['patient'])->latest()->get();
        
        if ($request->ajax()) {
            return response()->json($appointments);
        }

        $patients = \App\Models\Patient::all(); 
        $tests = \App\Models\LabTest::all();
        return view('appointments', compact('appointments', 'patients', 'tests'));
    }

    public function reports()
    {
        $reports = \App\Models\TestReport::with('patient')->latest()->get();
        $patients = \App\Models\Patient::orderBy('first_name')->get();
        $tests = \App\Models\LabTest::with(['parameter', 'referenceIntervals'])->orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        $subCategories = \App\Models\SubCategory::orderBy('name')->get();
        $units = \App\Models\Unit::orderBy('name')->get();
        $templates = \App\Models\ResultTemplate::orderBy('name')->get();

        return view('reports', compact('reports', 'patients', 'tests', 'categories', 'subCategories', 'units', 'templates'));
    }

    public function reportsTrash()
    {
        $reports = \App\Models\TestReport::onlyTrashed()->with('patient')->latest('deleted_at')->get();
        return view('reports_trash', compact('reports'));
    }

    public function storeReport(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_name' => 'required',
            'sample_received_on' => 'required|date',
            'report_released_on' => 'required|date',
            'test_name.*' => 'required',
            'observed_value.*' => 'required',
        ]);

        $patient = \App\Models\Patient::findOrFail($request->patient_id);
        $items = $this->buildReportItems($request, $patient);

        $report = \App\Models\TestReport::create([
            'patient_id' => $request->patient_id,
            'doctor_name' => $request->doctor_name,
            'sample_received_on' => $request->sample_received_on,
            'report_released_on' => $request->report_released_on,
            'barcode' => rand(100000, 999999),
            'status' => $request->status ?? 'Completed',
        ]);

        $report->items()->createMany($items);
        $this->auditReport($report, 'created', null, $this->reportSnapshot($report->fresh(['patient', 'items'])));

        // Update appointment status for these tests
        if ($request->has('test_name')) {
            \App\Models\Appointment::where('patient_id', $request->patient_id)
                ->whereIn('test_name', $request->test_name)
                ->where('status', '!=', 'Completed')
                ->update(['status' => 'Completed']);
        }

        return response()->json(['success' => 'Advanced test report generated successfully!']);
    }

    public function updateReport(\Illuminate\Http\Request $request, $id)
    {
        $report = \App\Models\TestReport::findOrFail($id);
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_name' => 'required',
            'sample_received_on' => 'required|date',
            'report_released_on' => 'required|date',
            'test_name.*' => 'required',
            'observed_value.*' => 'required',
        ]);

        $oldData = $this->reportSnapshot($report->load(['patient', 'items']));
        $patient = \App\Models\Patient::findOrFail($request->patient_id);
        $items = $this->buildReportItems($request, $patient);

        $report->update([
            'patient_id' => $request->patient_id,
            'doctor_name' => $request->doctor_name,
            'sample_received_on' => $request->sample_received_on,
            'report_released_on' => $request->report_released_on,
            'status' => $request->status ?? 'Completed',
        ]);
        $report->items()->delete();
        $report->items()->createMany($items);
        $this->auditReport($report, 'updated', $oldData, $this->reportSnapshot($report->fresh(['patient', 'items'])));

        return response()->json(['success' => 'Test report updated successfully!']);
    }

    public function getReport($id)
    {
        $report = \App\Models\TestReport::with(['patient', 'items'])->findOrFail($id);
        $payload = $report->toArray();
        $payload['results'] = $report->items->map(fn ($item) => $this->serializeReportItem($item))->values();
        return response()->json($payload);
    }

    public function deleteReport($id)
    {
        $report = \App\Models\TestReport::with(['patient', 'items'])->findOrFail($id);
        $this->auditReport($report, 'deleted', $this->reportSnapshot($report), null);
        $report->delete();
        return response()->json(['success' => 'Report deleted successfully!']);
    }

    public function restoreReport($id)
    {
        $report = \App\Models\TestReport::onlyTrashed()->findOrFail($id);
        $report->restore();
        $this->auditReport($report, 'restored', null, $this->reportSnapshot($report->fresh(['patient', 'items'])));

        return response()->json(['success' => 'Report restored successfully!']);
    }

    public function downloadPDF($id)
    {
        $report = \App\Models\TestReport::with(['patient', 'items'])->findOrFail($id);
        $patient = $report->patient;
        
        // Group results by category
        $groupedResults = [];
        foreach ($report->items as $item) {
            $result = $this->serializeReportItem($item);
            $cat = strtoupper($result['category'] ?? 'GENERAL');
            $groupedResults[$cat][] = $result;
        }

        $data = [
            'report' => $report,
            'patient' => $patient,
            'groupedResults' => $groupedResults,
            'generatedAt' => date('d-M-Y h:i A')
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.report', $data);
        
        $filename = 'Report_' . str_replace(' ', '_', $patient->first_name) . '_' . $report->id . '.pdf';
        return $pdf->download($filename);
    }

    public function patients()
    {
        $patients = \App\Models\Patient::with('appointments')->latest()->get();
        $labTests = \App\Models\LabTest::orderBy('name')->get();
        return view('patients', compact('patients', 'labTests'));
    }

    /**
     * Payments listing page
     */
    public function payments(\Illuminate\Http\Request $request)
    {
        $payments = \App\Models\Payment::with('patient')->latest()->get();
        $patients = \App\Models\Patient::orderBy('first_name')->get();

        if ($request->ajax()) {
            return response()->json($payments);
        }

        return view('payments', compact('payments', 'patients'));
    }

    public function storePayment(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'total_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'advance_paid' => 'nullable|numeric',
            'payment_status' => 'required',
            'payment_method' => 'required',
            'bill_date' => 'required|date',
            'remarks' => 'nullable'
        ]);

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['advance_paid'] = $validated['advance_paid'] ?? 0;
        $validated['net_amount'] = $validated['total_amount'] - $validated['discount'];
        $validated['balance_due'] = $validated['net_amount'] - $validated['advance_paid'];

        \App\Models\Payment::create($validated);

        return response()->json(['success' => 'Payment record created successfully!']);
    }

    public function getPayment($id)
    {
        $payment = \App\Models\Payment::with('patient')->findOrFail($id);
        return response()->json($payment);
    }

    public function updatePayment(\Illuminate\Http\Request $request, $id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'total_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'advance_paid' => 'nullable|numeric',
            'payment_status' => 'required',
            'payment_method' => 'required',
            'bill_date' => 'required|date',
            'remarks' => 'nullable'
        ]);

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['advance_paid'] = $validated['advance_paid'] ?? 0;
        $validated['net_amount'] = $validated['total_amount'] - $validated['discount'];
        $validated['balance_due'] = $validated['net_amount'] - $validated['advance_paid'];

        $payment->update($validated);

        return response()->json(['success' => 'Payment record updated successfully!']);
    }

    public function deletePayment($id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['success' => 'Payment record deleted successfully!']);
    }

    public function storePatient(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'nullable|unique:patients',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'age' => 'required|numeric',
            'phone' => 'nullable',
            'email' => 'required|email|unique:patients',
            'reference_dr' => 'nullable',
            'status' => 'nullable',
            'address' => 'nullable',
        ]);

        if (empty($validated['patient_id'])) {
            $latest = \App\Models\Patient::latest()->first();
            $nextId = ($latest ? $latest->id : 0) + 1;
            $validated['patient_id'] = date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }

        $validated['total_amount'] = 0;
        $validated['discount'] = 0;
        $validated['balance'] = 0;

        $patient = \App\Models\Patient::create($validated);

        return response()->json(['success' => 'Patient added successfully! ID: ' . $validated['patient_id']]);
    }

    public function getPatient($id)
    {
        $patient = \App\Models\Patient::with('appointments')->findOrFail($id);
        return response()->json($patient);
    }

    public function updatePatient(\Illuminate\Http\Request $request, $id)
    {
        $patient = \App\Models\Patient::findOrFail($id);
        
        $validated = $request->validate([
            'patient_id' => 'required|unique:patients,patient_id,' . $id,
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'age' => 'required|numeric',
            'phone' => 'nullable',
            'email' => 'required|email|unique:patients,email,' . $id,
            'reference_dr' => 'nullable',
            'status' => 'nullable',
            'address' => 'nullable',
        ]);

        $patient->update($validated);



        return response()->json(['success' => 'Patient updated successfully!']);
    }

    public function deletePatient($id)
    {
        $patient = \App\Models\Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['success' => 'Patient deleted successfully!']);
    }



    // Appointment CRUD Methods
    public function storeAppointment(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required',
            'doctor_name' => 'nullable',
            'test_name' => 'required',
            'test_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required',
            'reason' => 'nullable',
            'notes' => 'nullable',
        ]);

        // Auto-fill fields
        $validated['doctor_name'] = $validated['doctor_name'] ?? 'Self';
        $validated['total_amount'] = $validated['test_price'];
        $validated['discount'] = $request->discount ?? 0;
        $validated['balance'] = $request->balance ?? ($validated['test_price'] - $validated['discount']);

        \App\Models\Appointment::create($validated);

        return response()->json(['success' => 'Booking saved successfully!']);
    }

    public function getAppointment($id)
    {
        $appointment = \App\Models\Appointment::with('patient')->findOrFail($id);
        return response()->json($appointment);
    }

    public function updateAppointment(\Illuminate\Http\Request $request, $id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
        
        $validated = $request->validate([
            'patient_id' => 'required',
            'doctor_name' => 'required',
            'test_name' => 'required',
            'test_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required',
            'notes' => 'nullable',
        ]);

        $validated['total_amount'] = $validated['test_price'];
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['balance'] = $validated['balance'] ?? ($validated['test_price'] - $validated['discount']);

        $appointment->update($validated);

        return response()->json(['success' => 'Booking updated successfully!']);
    }

    public function deleteAppointment($id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['success' => 'Booking deleted successfully!']);
    }

    public function getDoctorSuggestions()
    {
        $doctors = \App\Models\Doctor::orderBy('name')->get();
        return response()->json($doctors);
    }

    public function storeDoctor(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'qualification' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email'
        ]);

        $doctor = \App\Models\Doctor::create($validated);
        return response()->json(['success' => 'Doctor added successfully!', 'doctor' => $doctor]);
    }

    public function updateDoctor(\Illuminate\Http\Request $request, $id)
    {
        $doctor = \App\Models\Doctor::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required',
            'qualification' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email'
        ]);

        // Optional: Update patients table if name changed
        if ($doctor->name !== $validated['name']) {
            \App\Models\Patient::where('reference_dr', $doctor->name)
                               ->update(['reference_dr' => $validated['name']]);
        }

        $doctor->update($validated);
        return response()->json(['success' => 'Doctor updated successfully!', 'doctor' => $doctor]);
    }

    public function deleteDoctor($id)
    {
        $doctor = \App\Models\Doctor::findOrFail($id);
        // Optional: nullify reference_dr in patients? 
        // \App\Models\Patient::where('reference_dr', $doctor->name)->update(['reference_dr' => null]);
        $doctor->delete();
        return response()->json(['success' => 'Doctor deleted successfully!']);
    }

    // ==========================================
    // PAYMENT MANAGEMENT
    // ==========================================

    public function incomeReport(\Illuminate\Http\Request $request)
    {
        if (!$request->session()->get('income_report_unlocked')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $query = \App\Models\Payment::with('patient')->orderBy('bill_date', 'desc');

        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $query->whereDate('bill_date', '>=', $startDate)
              ->whereDate('bill_date', '<=', $endDate);

        $payments = $query->get();
        
        $totalIncome = $payments->sum('net_amount');
        $totalTransactions = $payments->count();
        $averageTransaction = $totalTransactions > 0 ? $totalIncome / $totalTransactions : 0;

        // Daily trend
        $dailyTrend = $payments->groupBy('bill_date')->map(function ($row) {
            $income = $row->sum('net_amount');
            $transactions = $row->count();
            return [
                'date' => $row->first()->bill_date,
                'income' => $income,
                'transactions' => $transactions,
                'average' => $transactions > 0 ? $income / $transactions : 0,
            ];
        })->values()->take(30);

        // Payment method breakdown
        $methods = $payments->groupBy('payment_method')->map(function ($row) {
            return [
                'method' => $row->first()->payment_method ?: 'Unknown',
                'amount' => $row->sum('net_amount'),
                'count' => $row->count()
            ];
        })->values();

        // Recent transactions
        $recentTransactions = $payments;

        return view('income_report', compact(
            'totalIncome', 'totalTransactions', 'averageTransaction', 
            'dailyTrend', 'methods', 'recentTransactions'
        ));
    }

    public function unlockIncomeReport(\Illuminate\Http\Request $request)
    {
        $request->validate(['password' => 'required|string']);

        $expected = config('services.income_report.password');
        if (!$expected || !hash_equals($expected, $request->password)) {
            return response()->json(['message' => 'Invalid password.'], 422);
        }

        $request->session()->put('income_report_unlocked', true);

        return response()->json([
            'success' => true,
            'redirect' => route('income-report'),
        ]);
    }

    public function updateReportStatus(\Illuminate\Http\Request $request, $id)
    {
        $report = \App\Models\TestReport::findOrFail($id);
        $report->update(['status' => $request->status]);
        return response()->json(['success' => 'Report status updated live!']);
    }



    // ==========================================
    // LAB TEST MANAGEMENT
    // ==========================================

    public function storeLabTest(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        \App\Models\LabTest::create($validated);

        return response()->json(['success' => 'Laboratory test registered successfully!']);
    }

    public function updateLabTest(\Illuminate\Http\Request $request, $id)
    {
        $test = \App\Models\LabTest::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        $test->update($validated);

        return response()->json(['success' => 'Laboratory test updated successfully!']);
    }

    public function deleteLabTest($id)
    {
        $test = \App\Models\LabTest::findOrFail($id);
        $test->delete();

        return response()->json(['success' => 'Laboratory test removed successfully!']);
    }

    // ==========================================
    // CATEGORY MANAGEMENT
    // ==========================================

    public function categories()
    {
        $categories = \App\Models\Category::latest()->get();
        return view('categories', compact('categories'));
    }

    public function storeCategory(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'nullable',
        ]);

        \App\Models\Category::create($validated);

        return response()->json(['success' => 'Category added successfully!']);
    }

    public function updateCategory(\Illuminate\Http\Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'description' => 'nullable',
        ]);

        $category->update($validated);

        return response()->json(['success' => 'Category updated successfully!']);
    }

    public function deleteCategory($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => 'Category deleted successfully!']);
    }

    // ==========================================
    // SUB-CATEGORY MANAGEMENT
    // ==========================================

    public function subCategories()
    {
        $subCategories = \App\Models\SubCategory::with('category')->latest()->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('sub_categories', compact('subCategories', 'categories'));
    }

    public function storeSubCategory(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'description' => 'nullable',
        ]);

        \App\Models\SubCategory::create($validated);

        return response()->json(['success' => 'Sub-Category added successfully!']);
    }

    public function updateSubCategory(\Illuminate\Http\Request $request, $id)
    {
        $subCategory = \App\Models\SubCategory::findOrFail($id);
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $subCategory->update($validated);

        return response()->json(['success' => 'Sub-Category updated successfully!']);
    }

    public function deleteSubCategory($id)
    {
        $subCategory = \App\Models\SubCategory::findOrFail($id);
        $subCategory->delete();

        return response()->json(['success' => 'Sub-Category deleted successfully!']);
    }

    public function masterData()
    {
        $units = \App\Models\Unit::orderBy('name')->get();
        $templates = \App\Models\ResultTemplate::orderBy('name')->get();
        return view('master_data', compact('units', 'templates'));
    }

    public function storeUnit(\Illuminate\Http\Request $request)
    {
        $request->validate(['name' => 'required|unique:units']);
        \App\Models\Unit::create($request->all());
        return response()->json(['success' => 'Unit added successfully!']);
    }

    public function updateUnit(\Illuminate\Http\Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:units,name,'.$id]);
        $unit = \App\Models\Unit::findOrFail($id);
        $unit->update($request->all());
        return response()->json(['success' => 'Unit updated!']);
    }

    public function deleteUnit($id)
    {
        \App\Models\Unit::destroy($id);
        return response()->json(['success' => 'Unit removed!']);
    }

    public function storeResultTemplate(\Illuminate\Http\Request $request)
    {
        $request->validate(['name' => 'required|unique:result_templates']);
        \App\Models\ResultTemplate::create($request->all());
        return response()->json(['success' => 'Result template added!']);
    }

    public function updateResultTemplate(\Illuminate\Http\Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:result_templates,name,'.$id]);
        $template = \App\Models\ResultTemplate::findOrFail($id);
        $template->update($request->all());
        return response()->json(['success' => 'Template updated!']);
    }

    public function deleteResultTemplate($id)
    {
        \App\Models\ResultTemplate::destroy($id);
        return response()->json(['success' => 'Template removed!']);
    }

    public function testParameters()
    {
        $tests = \App\Models\LabTest::with(['parameter', 'referenceIntervals'])->orderBy('name')->get();
        $units = \App\Models\Unit::orderBy('name')->get();
        return view('test_parameters', compact('tests', 'units'));
    }

    public function storeTestParameter(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'lab_test_id' => 'required|exists:lab_tests,id',
            'unit' => 'nullable',
            'male_reference' => 'nullable',
            'female_reference' => 'nullable',
            'male_min' => 'nullable|numeric',
            'male_max' => 'nullable|numeric',
            'female_min' => 'nullable|numeric',
            'female_max' => 'nullable|numeric',
            'critical_low' => 'nullable|numeric',
            'critical_high' => 'nullable|numeric',
            'biological_reference' => 'nullable',
            'is_immunoassay' => 'nullable|boolean',
        ]);

        $parameter = \App\Models\TestParameter::updateOrCreate(
            ['lab_test_id' => $validated['lab_test_id']],
            $validated
        );

        $this->upsertDefaultReferenceInterval($parameter, 'Male');
        $this->upsertDefaultReferenceInterval($parameter, 'Female');

        return response()->json(['success' => 'Parameters updated successfully!']);
    }

    private function buildReportItems(\Illuminate\Http\Request $request, \App\Models\Patient $patient): array
    {
        $tests = \App\Models\LabTest::with(['parameter', 'referenceIntervals'])->get()->keyBy('name');
        $items = [];

        foreach ($request->test_name ?? [] as $key => $name) {
            $labTest = $tests->get($name);
            $parameter = $labTest?->parameter;
            $reference = $this->resolveReferenceInterval($labTest, $patient);
            $normalValue = $reference['text']
                ?? ($request->normal_value[$key] ?? $request->biological_reference[$key] ?? '');
            $unit = $request->test_unit[$key] ?? $parameter?->unit ?? '';
            $flag = $this->calculateResultFlag(
                $request->observed_value[$key] ?? null,
                $parameter,
                $reference,
                $patient->gender
            ) ?? ($request->test_flag[$key] ?? '');

            $items[] = [
                'lab_test_id' => $labTest?->id,
                'category' => ($request->test_category[$key] ?? '') ?: 'General',
                'subcategory' => $request->test_subcategory[$key] ?? '',
                'name' => $name,
                'observed_value' => $request->observed_value[$key],
                'unit' => $unit,
                'normal_value' => $normalValue,
                'biological_reference' => $request->biological_reference[$key] ?? $normalValue,
                'flag' => $flag,
                'sort_order' => $key,
            ];
        }

        return $items;
    }

    private function resolveReferenceInterval(?\App\Models\LabTest $labTest, \App\Models\Patient $patient): array
    {
        if (!$labTest) {
            return [];
        }

        $age = (int) $patient->age;
        $gender = strtolower((string) $patient->gender);
        $interval = $labTest->referenceIntervals
            ->filter(function ($interval) use ($age, $gender) {
                $genderMatches = !$interval->gender || strtolower($interval->gender) === $gender;
                $minMatches = $interval->age_min === null || $age >= (int) $interval->age_min;
                $maxMatches = $interval->age_max === null || $age <= (int) $interval->age_max;
                return $genderMatches && $minMatches && $maxMatches;
            })
            ->sortByDesc('age_min')
            ->first();

        if ($interval) {
            return [
                'text' => $interval->reference_text,
                'min' => $interval->min_value,
                'max' => $interval->max_value,
            ];
        }

        $parameter = $labTest->parameter;
        if (!$parameter) {
            return [];
        }

        if ($gender === 'female') {
            return [
                'text' => $parameter->female_reference ?: $parameter->biological_reference,
                'min' => $parameter->female_min,
                'max' => $parameter->female_max,
            ];
        }

        return [
            'text' => $parameter->male_reference ?: $parameter->biological_reference,
            'min' => $parameter->male_min,
            'max' => $parameter->male_max,
        ];
    }

    private function calculateResultFlag(mixed $observedValue, ?\App\Models\TestParameter $parameter, array $reference, ?string $gender): ?string
    {
        if (!$parameter || !is_numeric($observedValue)) {
            return null;
        }

        $value = (float) $observedValue;

        if ($parameter->critical_low !== null && $value <= (float) $parameter->critical_low) {
            return 'C';
        }

        if ($parameter->critical_high !== null && $value >= (float) $parameter->critical_high) {
            return 'C';
        }

        if ($parameter->is_immunoassay) {
            if ($value < 0.9) {
                return 'N';
            }
            if ($value <= 1.1) {
                return 'B';
            }
            return 'P';
        }

        $min = $reference['min'] ?? null;
        $max = $reference['max'] ?? null;

        if ($min === null && $max === null) {
            $isFemale = strtolower((string) $gender) === 'female';
            $min = $isFemale ? $parameter->female_min : $parameter->male_min;
            $max = $isFemale ? $parameter->female_max : $parameter->male_max;
        }

        if ($min !== null && $value < (float) $min) {
            return 'L';
        }

        if ($max !== null && $value > (float) $max) {
            return 'H';
        }

        return ($min !== null || $max !== null) ? 'N' : null;
    }

    private function serializeReportItem(\App\Models\TestReportItem $item): array
    {
        return [
            'category' => $item->category,
            'subcategory' => $item->subcategory,
            'name' => $item->name,
            'observed_value' => $item->observed_value,
            'unit' => $item->unit,
            'normal_value' => $item->normal_value,
            'biological_reference' => $item->biological_reference,
            'flag' => $item->flag,
        ];
    }

    private function reportSnapshot(\App\Models\TestReport $report): array
    {
        if (!$report->relationLoaded('items')) {
            $report->load('items');
        }

        return [
            'report' => $report->only([
                'id',
                'patient_id',
                'doctor_name',
                'sample_received_on',
                'report_released_on',
                'barcode',
                'status',
            ]),
            'results' => $report->items->map(fn ($item) => $this->serializeReportItem($item))->values()->all(),
        ];
    }

    private function auditReport(\App\Models\TestReport $report, string $action, ?array $oldData, ?array $newData): void
    {
        \App\Models\TestReportAudit::create([
            'test_report_id' => $report->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

    private function upsertDefaultReferenceInterval(\App\Models\TestParameter $parameter, string $gender): void
    {
        $isFemale = strtolower($gender) === 'female';

        \App\Models\ReferenceInterval::updateOrCreate(
            [
                'lab_test_id' => $parameter->lab_test_id,
                'gender' => $gender,
                'age_min' => 0,
                'age_max' => 200,
            ],
            [
                'reference_text' => $isFemale ? $parameter->female_reference : $parameter->male_reference,
                'min_value' => $isFemale ? $parameter->female_min : $parameter->male_min,
                'max_value' => $isFemale ? $parameter->female_max : $parameter->male_max,
            ]
        );
    }
}
