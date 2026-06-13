<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $units = \App\Models\Unit::orderBy('name')->get();
        return view('appointments', compact('appointments', 'patients', 'tests', 'units'));
    }

    //

    public function toggleSidebar(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $user->sidebar_collapsed = !$user->sidebar_collapsed;
        $user->save();

        return response()->json(['success' => true, 'collapsed' => $user->sidebar_collapsed]);
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
        $referenceTemplates = \App\Models\ReferenceTemplate::orderBy('name')->get();
        $flagTemplates = \App\Models\FlagTemplate::orderBy('name')->get();
        $signatures = \App\Models\ReportSignature::orderBy('name')->get();

        return view('reports', compact('reports', 'patients', 'tests', 'categories', 'subCategories', 'units', 'templates', 'referenceTemplates', 'flagTemplates', 'signatures'));
    }

    public function reportSignatures()
    {
        $signatures = \App\Models\ReportSignature::latest()->get();
        return view('report_signatures', compact('signatures'));
    }

    public function storeReportSignature(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'signature_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'pin' => 'required|string|min:4|max:20',
        ]);

        $path = $request->file('signature_image')->store('report-signatures', 'public');

        \App\Models\ReportSignature::create([
            'name' => $validated['name'],
            'image_path' => $path,
            'pin_hash' => \Illuminate\Support\Facades\Hash::make($validated['pin']),
        ]);

        return redirect()->route('report-signatures.index')->with('success', 'Signature added successfully.');
    }

    public function updateReportSignature(\Illuminate\Http\Request $request, $id)
    {
        $signature = \App\Models\ReportSignature::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'signature_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'pin' => 'nullable|string|min:4|max:20',
        ]);

        $payload = ['name' => $validated['name']];

        if ($request->hasFile('signature_image')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($signature->image_path);
            $payload['image_path'] = $request->file('signature_image')->store('report-signatures', 'public');
        }

        if (!empty($validated['pin'])) {
            $payload['pin_hash'] = \Illuminate\Support\Facades\Hash::make($validated['pin']);
        }

        $signature->update($payload);

        return redirect()->route('report-signatures.index')->with('success', 'Signature updated successfully.');
    }

    public function deleteReportSignature($id)
    {
        $signature = \App\Models\ReportSignature::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($signature->image_path);
        $signature->delete();

        return response()->json(['success' => 'Signature deleted successfully.']);
    }

    public function reportSignatureImage($id)
    {
        $signature = \App\Models\ReportSignature::findOrFail($id);
        abort_unless(\Illuminate\Support\Facades\Storage::disk('public')->exists($signature->image_path), 404);

        return response()->file($signature->imageAbsolutePath());
    }

    public function storeReport(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_name' => 'required',
            'sample_received_on' => 'required|date',
            'report_released_on' => 'required|date',
            'notes' => 'nullable|string',
            'report_signature_id' => 'nullable|exists:report_signatures,id',
            'signature_pin' => 'required_with:report_signature_id|nullable|string',
            'test_name.*' => 'nullable',
            'observed_value.*' => 'nullable',
        ]);

        $patient = \App\Models\Patient::findOrFail($request->patient_id);
        $items = $this->buildReportItems($request, $patient);
        $signatureId = $this->verifiedSignatureId($request);

        $report = \App\Models\TestReport::create([
            'patient_id' => $request->patient_id,
            'doctor_name' => $request->doctor_name,
            'sample_received_on' => $request->sample_received_on,
            'report_released_on' => $request->report_released_on,
            'barcode' => now()->format('ymd') . mt_rand(1000, 9999),
            'status' => $request->status ?? 'Completed',
            'notes' => $request->notes,
            'report_signature_id' => $signatureId,
        ]);

        $report->items()->createMany($items);
        $this->auditReport($report, 'created', null, $this->reportSnapshot($report->fresh(['patient', 'items', 'signature'])));

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
            'notes' => 'nullable|string',
            'report_signature_id' => 'nullable|exists:report_signatures,id',
            'signature_pin' => 'required_with:report_signature_id|nullable|string',
            'test_name.*' => 'nullable',
            'observed_value.*' => 'nullable',
        ]);

        $oldData = $this->reportSnapshot($report->load(['patient', 'items', 'signature']));
        $patient = \App\Models\Patient::findOrFail($request->patient_id);
        $items = $this->buildReportItems($request, $patient);
        $signatureId = $this->verifiedSignatureId($request);

        $report->update([
            'patient_id' => $request->patient_id,
            'doctor_name' => $request->doctor_name,
            'sample_received_on' => $request->sample_received_on,
            'report_released_on' => $request->report_released_on,
            'status' => $request->status ?? 'Completed',
            'notes' => $request->notes,
            'report_signature_id' => $signatureId,
        ]);
        $report->items()->delete();
        $report->items()->createMany($items);
        $this->auditReport($report, 'updated', $oldData, $this->reportSnapshot($report->fresh(['patient', 'items', 'signature'])));

        return response()->json(['success' => 'Test report updated successfully!']);
    }

    public function getReport($id)
    {
        $report = \App\Models\TestReport::with(['patient', 'items', 'signature'])->findOrFail($id);
        $payload = $report->toArray();
        $payload['results'] = $report->items->map(fn ($item) => $this->serializeReportItem($item))->values();
        $payload['signature'] = $this->serializeReportSignature($report->signature);
        return response()->json($payload);
    }

    public function deleteReport($id)
    {
        $report = \App\Models\TestReport::with(['patient', 'items', 'signature'])->findOrFail($id);
        $this->auditReport($report, 'deleted', $this->reportSnapshot($report), null);
        $report->delete();
        return response()->json(['success' => 'Report deleted successfully!']);
    }

    public function downloadPDF($id)
    {
        $report = \App\Models\TestReport::with(['patient', 'items', 'signature'])->findOrFail($id);
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
            'patient_id'     => 'required|exists:patients,id',
            'total_amount'   => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'advance_paid'   => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:Paid,Partial,Unpaid,Refunded',
            'payment_method' => 'required|in:Cash,Card,Bank Transfer,Online,Other',
            'bill_date'      => 'required|date',
            'remarks'        => 'nullable|string|max:500',
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
            'patient_id'     => 'required|exists:patients,id',
            'total_amount'   => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'advance_paid'   => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:Paid,Partial,Unpaid,Refunded',
            'payment_method' => 'required|in:Cash,Card,Bank Transfer,Online,Other',
            'bill_date'      => 'required|date',
            'remarks'        => 'nullable|string|max:500',
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
            'patient_id'     => 'nullable|unique:patients',
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'gender'         => 'required|in:Male,Female,Other',
            'age'            => 'required|numeric|min:0|max:150',
            'age_type'       => 'nullable|in:Years,Months,Days',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'reference_dr'   => 'nullable|string|max:150',
            'status'         => 'nullable|string|max:50',
            'address'        => 'nullable|string|max:500',
            'payment_method' => 'nullable|string|max:50',
        ]);

        if (empty($validated['patient_id'])) {
            $validated['patient_id'] = DB::transaction(function () {
                $latest = \App\Models\Patient::lockForUpdate()->latest('id')->first();
                $nextId = ($latest ? $latest->id : 0) + 1;
                return date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            });
        }

        // Calculate totals from input test arrays
        $testNames = $request->input('test_name', []);
        $testPrices = $request->input('test_price', []);
        $testDiscounts = $request->input('test_discount', []);

        $totalAmount = 0;
        $totalDiscount = 0;

        foreach ($testNames as $index => $name) {
            if (!empty($name)) {
                $price = floatval($testPrices[$index] ?? 0);
                $discount = floatval($testDiscounts[$index] ?? 0);
                $totalAmount += $price;
                $totalDiscount += $discount;
            }
        }

        $validated['total_amount'] = $totalAmount;
        $validated['discount'] = $totalDiscount;
        $validated['balance'] = $totalAmount - $totalDiscount;

        $patient = \App\Models\Patient::create($validated);

        // Create appointments
        foreach ($testNames as $index => $name) {
            if (!empty($name)) {
                $price = floatval($testPrices[$index] ?? 0);
                $discount = floatval($testDiscounts[$index] ?? 0);
                $balance = $price - $discount;

                \App\Models\Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_name' => $patient->reference_dr ?: 'Self',
                    'test_name' => $name,
                    'test_price' => $price,
                    'discount' => $discount,
                    'balance' => $balance,
                    'appointment_date' => date('Y-m-d'),
                    'appointment_time' => date('H:i'),
                    'status' => 'Pending',
                    'total_amount' => $price,
                ]);
            }
        }

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
            'patient_id'     => 'required|unique:patients,patient_id,' . $id,
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'gender'         => 'required|in:Male,Female,Other',
            'age'            => 'required|numeric|min:0|max:150',
            'age_type'       => 'nullable|in:Years,Months,Days',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'reference_dr'   => 'nullable|string|max:150',
            'status'         => 'nullable|string|max:50',
            'address'        => 'nullable|string|max:500',
            'payment_method' => 'nullable|string|max:50',
        ]);

        // Calculate totals from input test arrays
        $appointmentIds = $request->input('appointment_id', []);
        $testNames = $request->input('test_name', []);
        $testPrices = $request->input('test_price', []);
        $testDiscounts = $request->input('test_discount', []);

        $totalAmount = 0;
        $totalDiscount = 0;

        foreach ($testNames as $index => $name) {
            if (!empty($name)) {
                $price = floatval($testPrices[$index] ?? 0);
                $discount = floatval($testDiscounts[$index] ?? 0);
                $totalAmount += $price;
                $totalDiscount += $discount;
            }
        }

        $validated['total_amount'] = $totalAmount;
        $validated['discount'] = $totalDiscount;
        $validated['balance'] = $totalAmount - $totalDiscount;

        $patient->update($validated);

        // Keep track of active/updated appointment IDs so we can delete removed ones
        $keepAppointmentIds = [];

        foreach ($testNames as $index => $name) {
            if (!empty($name)) {
                $appId = $appointmentIds[$index] ?? null;
                $price = floatval($testPrices[$index] ?? 0);
                $discount = floatval($testDiscounts[$index] ?? 0);
                $balance = $price - $discount;

                if ($appId) {
                    $appointment = \App\Models\Appointment::find($appId);
                    if ($appointment) {
                        $appointment->update([
                            'test_name' => $name,
                            'test_price' => $price,
                            'discount' => $discount,
                            'balance' => $balance,
                            'total_amount' => $price,
                        ]);
                        $keepAppointmentIds[] = $appointment->id;
                    }
                } else {
                    $newApp = \App\Models\Appointment::create([
                        'patient_id' => $patient->id,
                        'doctor_name' => $patient->reference_dr ?: 'Self',
                        'test_name' => $name,
                        'test_price' => $price,
                        'discount' => $discount,
                        'balance' => $balance,
                        'appointment_date' => date('Y-m-d'),
                        'appointment_time' => date('H:i'),
                        'status' => 'Pending',
                        'total_amount' => $price,
                    ]);
                    $keepAppointmentIds[] = $newApp->id;
                }
            }
        }

        // Delete any appointments that were removed from the edit list
        \App\Models\Appointment::where('patient_id', $patient->id)
            ->whereNotIn('id', $keepAppointmentIds)
            ->delete();

        return response()->json(['success' => 'Patient updated successfully!']);
    }

    public function deletePatient($id)
    {
        $patient = \App\Models\Patient::findOrFail($id);

        if (\App\Models\TestReport::where('patient_id', $patient->id)->exists()) {
            return response()->json(['error' => 'Cannot delete patient with existing reports. Delete or reassign reports first.'], 409);
        }

        $patient->delete();

        return response()->json(['success' => 'Patient deleted successfully!']);
    }



    // Appointment CRUD Methods
    public function storeAppointment(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_name' => 'nullable|string|max:150',
            'test_name' => 'required|string|max:255',
            'test_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'balance' => 'nullable|numeric',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:Pending,Completed,Cancelled',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
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
            'patient_id' => 'required|exists:patients,id',
            'doctor_name' => 'required|string|max:150',
            'test_name' => 'required|string|max:255',
            'test_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'balance' => 'nullable|numeric',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:Pending,Completed,Cancelled',
            'notes' => 'nullable|string|max:1000',
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
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed,Cancelled',
        ]);

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
            'price' => 'nullable|numeric',
            'payment_method' => 'nullable',
            'description' => 'nullable',
        ]);

        if (empty($validated['price']) && $validated['price'] !== 0) {
            $validated['price'] = 0;
        }

        $test = \App\Models\LabTest::create($validated);

        return response()->json(['success' => 'Laboratory test registered successfully!', 'test' => $test]);
    }

    public function quickStoreTest(\Illuminate\Http\Request $request)
    {
        $validatedTest = $request->validate([
            'name' => 'required',
            'price' => 'nullable|numeric',
            'description' => 'nullable'
        ]);

        if (!isset($validatedTest['price']) || $validatedTest['price'] === null) {
            $validatedTest['price'] = 0;
        }

        $test = \App\Models\LabTest::create($validatedTest);

        $paramData = $request->only([
            'unit', 'male_reference', 'female_reference',
            'male_min', 'male_max', 'female_min', 'female_max',
            'critical_low', 'critical_high', 'biological_reference', 'is_immunoassay'
        ]);
        $paramData['lab_test_id'] = $test->id;

        \App\Models\TestParameter::create($paramData);

        return response()->json(['success' => 'Parameter created successfully!', 'test' => $test]);
    }

    public function updateLabTest(\Illuminate\Http\Request $request, $id)
    {
        $test = \App\Models\LabTest::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'payment_method' => 'nullable',
            'description' => 'nullable',
        ]);

        $test->update($validated);

        return response()->json(['success' => 'Laboratory test updated successfully!', 'test' => $test]);
    }

    public function quickUpdateTest(\Illuminate\Http\Request $request, $id)
    {
        $test = \App\Models\LabTest::findOrFail($id);
        
        $validatedTest = $request->validate([
            'name' => 'required',
            'price' => 'nullable|numeric',
            'description' => 'nullable'
        ]);

        if (!isset($validatedTest['price']) || $validatedTest['price'] === null) {
            $validatedTest['price'] = 0;
        }

        $test->update($validatedTest);

        $paramData = $request->only([
            'unit', 'male_reference', 'female_reference',
            'male_min', 'male_max', 'female_min', 'female_max',
            'critical_low', 'critical_high', 'biological_reference', 'is_immunoassay'
        ]);

        $parameter = \App\Models\TestParameter::updateOrCreate(
            ['lab_test_id' => $test->id],
            $paramData
        );

        return response()->json(['success' => 'Parameter updated successfully!', 'test' => $test]);
    }

    public function apiTests()
    {
        $tests = \App\Models\LabTest::with(['parameter', 'referenceIntervals'])->get();
        return response()->json($tests);
    }

    public function deleteLabTest($id)
    {
        $test = \App\Models\LabTest::findOrFail($id);

        if (\App\Models\TestReportItem::where('lab_test_id', $test->id)->exists()) {
            return response()->json(['error' => 'Cannot delete test that is used in existing reports.'], 409);
        }

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

        $category = \App\Models\Category::create($validated);

        return response()->json(['success' => 'Category added successfully!', 'category' => $category]);
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

        $subCategory = \App\Models\SubCategory::create($validated);

        return response()->json(['success' => 'Sub-Category added successfully!', 'subCategory' => $subCategory]);
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
        $referenceTemplates = \App\Models\ReferenceTemplate::orderBy('name')->get();
        $flagTemplates = \App\Models\FlagTemplate::orderBy('name')->get();
        return view('master_data', compact('units', 'templates', 'referenceTemplates', 'flagTemplates'));
    }

    public function apiUnits()
    {
        $units = \App\Models\Unit::orderBy('name')->get();
        return response()->json($units);
    }

    public function storeUnit(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:units']);
        $unit = \App\Models\Unit::create($validated);
        return response()->json(['success' => 'Unit added successfully!', 'unit' => $unit]);
    }

    public function updateUnit(\Illuminate\Http\Request $request, $id)
    {
        $validated = $request->validate(['name' => 'required|unique:units,name,'.$id]);
        $unit = \App\Models\Unit::findOrFail($id);
        $unit->update($validated);
        return response()->json(['success' => 'Unit updated!', 'unit' => $unit]);
    }

    public function deleteUnit($id)
    {
        \App\Models\Unit::destroy($id);
        return response()->json(['success' => 'Unit removed!']);
    }

    public function storeResultTemplate(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:result_templates']);
        \App\Models\ResultTemplate::create($validated);
        return response()->json(['success' => 'Result template added!']);
    }

    public function updateResultTemplate(\Illuminate\Http\Request $request, $id)
    {
        $validated = $request->validate(['name' => 'required|unique:result_templates,name,'.$id]);
        $template = \App\Models\ResultTemplate::findOrFail($id);
        $template->update($validated);
        return response()->json(['success' => 'Template updated!']);
    }

    public function deleteResultTemplate($id)
    {
        \App\Models\ResultTemplate::destroy($id);
        return response()->json(['success' => 'Template removed!']);
    }

    public function storeReferenceTemplate(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:reference_templates']);
        \App\Models\ReferenceTemplate::create($validated);
        return response()->json(['success' => 'Reference template added!']);
    }

    public function updateReferenceTemplate(\Illuminate\Http\Request $request, $id)
    {
        $validated = $request->validate(['name' => 'required|unique:reference_templates,name,'.$id]);
        $template = \App\Models\ReferenceTemplate::findOrFail($id);
        $template->update($validated);
        return response()->json(['success' => 'Template updated!']);
    }

    public function deleteReferenceTemplate($id)
    {
        \App\Models\ReferenceTemplate::destroy($id);
        return response()->json(['success' => 'Template removed!']);
    }

    public function storeFlagTemplate(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:flag_templates']);
        \App\Models\FlagTemplate::create($validated);
        return response()->json(['success' => 'Flag template added!']);
    }

    public function updateFlagTemplate(\Illuminate\Http\Request $request, $id)
    {
        $validated = $request->validate(['name' => 'required|unique:flag_templates,name,'.$id]);
        $template = \App\Models\FlagTemplate::findOrFail($id);
        $template->update($validated);
        return response()->json(['success' => 'Template updated!']);
    }

    public function deleteFlagTemplate($id)
    {
        \App\Models\FlagTemplate::destroy($id);
        return response()->json(['success' => 'Template removed!']);
    }

    public function apiReferenceTemplates()
    {
        return response()->json(\App\Models\ReferenceTemplate::orderBy('name')->get());
    }

    public function apiResultTemplates()
    {
        return response()->json(\App\Models\ResultTemplate::orderBy('name')->get());
    }

    public function apiFlagTemplates()
    {
        return response()->json(\App\Models\FlagTemplate::orderBy('name')->get());
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
            // Skip empty rows — Dynamic Test Results is fully optional
            if (empty(trim((string) $name))) {
                continue;
            }

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
                'observed_value' => $request->observed_value[$key] ?? null,
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
        $ageType = strtolower((string) ($patient->age_type ?: 'Years'));
        $interval = $labTest->referenceIntervals
            ->filter(function ($interval) use ($age, $gender, $ageType) {
                $genderMatches = !$interval->gender || strtolower($interval->gender) === $gender || strtolower($interval->gender) === 'any';
                $minMatches = $interval->age_min === null || $age >= (int) $interval->age_min;
                $maxMatches = $interval->age_max === null || $age <= (int) $interval->age_max;
                $ageTypeMatches = !$interval->age_type || strtolower($interval->age_type) === $ageType || strtolower($interval->age_type) === 'any';
                return $genderMatches && $minMatches && $maxMatches && $ageTypeMatches;
            })
            ->sortBy(function ($interval) {
                $min = $interval->age_min ?? 0;
                $max = $interval->age_max ?? 200;
                return $max - $min;
            })
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

        $maleRef = $parameter->male_reference;
        $femaleRef = $parameter->female_reference;
        
        $textStr = '';
        if ($maleRef && $femaleRef && $maleRef !== $femaleRef) {
            $textStr = "Male: " . $maleRef . "\nFemale: " . $femaleRef;
        } else {
            $textStr = ($gender === 'female') ? ($femaleRef ?: $parameter->biological_reference) : ($maleRef ?: $parameter->biological_reference);
        }

        if ($gender === 'female') {
            return [
                'text' => $textStr,
                'min' => $parameter->female_min,
                'max' => $parameter->female_max,
            ];
        }

        return [
            'text' => $textStr,
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

        if ($parameter->critical_low !== null && $parameter->critical_low !== '' && $value <= (float) $parameter->critical_low) {
            return 'C';
        }

        if ($parameter->critical_high !== null && $parameter->critical_high !== '' && $value >= (float) $parameter->critical_high) {
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

    private function verifiedSignatureId(\Illuminate\Http\Request $request): ?int
    {
        if (!$request->filled('report_signature_id')) {
            return null;
        }

        $signature = \App\Models\ReportSignature::findOrFail($request->report_signature_id);
        if (!\Illuminate\Support\Facades\Hash::check((string) $request->signature_pin, $signature->pin_hash)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'signature_pin' => 'Invalid signature PIN.',
            ]);
        }

        return $signature->id;
    }

    private function serializeReportSignature(?\App\Models\ReportSignature $signature): ?array
    {
        if (!$signature) {
            return null;
        }

        $path = $signature->imageAbsolutePath();
        $imageData = null;
        if (is_file($path)) {
            $mime = mime_content_type($path) ?: 'image/png';
            $imageData = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        return [
            'id' => $signature->id,
            'name' => $signature->name,
            'image_url' => route('report-signatures.image', $signature->id),
            'image_data' => $imageData,
        ];
    }

    private function reportSnapshot(\App\Models\TestReport $report): array
    {
        if (!$report->relationLoaded('items')) {
            $report->load('items');
        }
        if (!$report->relationLoaded('signature')) {
            $report->load('signature');
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
                'notes',
                'report_signature_id',
            ]),
            'signature' => $report->signature ? $report->signature->only(['id', 'name', 'image_path']) : null,
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

    public function getReferenceIntervals($id)
    {
        return response()->json(\App\Models\ReferenceInterval::where('lab_test_id', $id)->get());
    }

    public function storeReferenceInterval(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'gender' => 'required|string',
            'age_min' => 'nullable|numeric',
            'age_max' => 'nullable|numeric',
            'age_type' => 'nullable|string',
            'reference_text' => 'nullable|string',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
        ]);

        $data = $request->only(['gender', 'age_min', 'age_max', 'age_type', 'reference_text', 'min_value', 'max_value']);
        // Database requires age_min to be non-null. Default to 0.
        if (empty($data['age_min'])) {
            $data['age_min'] = 0;
        }

        if ($request->has('interval_id') && $request->interval_id) {
            $interval = \App\Models\ReferenceInterval::findOrFail($request->interval_id);
            $interval->update($data);
        } else {
            \App\Models\ReferenceInterval::create(array_merge($data, ['lab_test_id' => $id]));
        }

        return response()->json(['success' => 'Interval added successfully']);
    }

    public function deleteReferenceInterval($id)
    {
        $interval = \App\Models\ReferenceInterval::findOrFail($id);
        $interval->delete();

        return response()->json(['success' => 'Interval deleted successfully']);
    }


    // ── VITAL SIGNS METHODS ──

    public function vitalSigns()
    {
        $vitalSigns = \App\Models\VitalSign::with('patient')->latest()->get();
        $patients = \App\Models\Patient::orderBy('first_name')->get();
        return view('vital_signs', compact('vitalSigns', 'patients'));
    }

    public function storeVitalSign(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'temperature'      => 'nullable|numeric|between:10,120',
            'temp_unit'        => 'nullable|in:C,F',
            'pulse'            => 'nullable|integer|between:10,300',
            'respiratory_rate' => 'nullable|integer|between:2,100',
            'blood_pressure'   => 'nullable|string|max:20',
            'spo2'             => 'nullable|integer|between:0,100',
            'weight'           => 'nullable|numeric|between:0.1,500',
            'height'           => 'nullable|numeric|between:10,300',
            'notes'            => 'nullable|string|max:1000',
        ]);

        if (empty($validated['temp_unit'])) {
            $validated['temp_unit'] = 'F';
        }

        // Auto-calculate BMI if weight and height are provided
        if (!empty($validated['weight']) && !empty($validated['height'])) {
            $heightInMeters = $validated['height'] / 100;
            $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 1);
        } else {
            $validated['bmi'] = null;
        }

        \App\Models\VitalSign::create($validated);

        return response()->json(['success' => 'Vital Signs recorded successfully!']);
    }

    public function getVitalSign($id)
    {
        $vitalSign = \App\Models\VitalSign::with('patient')->findOrFail($id);
        return response()->json($vitalSign);
    }

    public function updateVitalSign(\Illuminate\Http\Request $request, $id)
    {
        $vitalSign = \App\Models\VitalSign::findOrFail($id);

        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'temperature'      => 'nullable|numeric|between:10,120',
            'temp_unit'        => 'nullable|in:C,F',
            'pulse'            => 'nullable|integer|between:10,300',
            'respiratory_rate' => 'nullable|integer|between:2,100',
            'blood_pressure'   => 'nullable|string|max:20',
            'spo2'             => 'nullable|integer|between:0,100',
            'weight'           => 'nullable|numeric|between:0.1,500',
            'height'           => 'nullable|numeric|between:10,300',
            'notes'            => 'nullable|string|max:1000',
        ]);

        if (empty($validated['temp_unit'])) {
            $validated['temp_unit'] = 'F';
        }

        // Auto-calculate BMI if weight and height are provided
        if (!empty($validated['weight']) && !empty($validated['height'])) {
            $heightInMeters = $validated['height'] / 100;
            $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 1);
        } else {
            $validated['bmi'] = null;
        }

        $vitalSign->update($validated);

        return response()->json(['success' => 'Vital Signs updated successfully!']);
    }

    public function deleteVitalSign($id)
    {
        $vitalSign = \App\Models\VitalSign::findOrFail($id);
        $vitalSign->delete();

        return response()->json(['success' => 'Vital Signs deleted successfully!']);
    }
}


