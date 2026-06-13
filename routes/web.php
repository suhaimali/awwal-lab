<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Public Landing Page
Route::get('/', function() {
    return view('welcome');
})->name('home');

// Protected Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Redirect /home to dashboard
    Route::get('/home', function() { return redirect()->route('dashboard'); });

    // Dashboard served by HomeController@index
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/about', function() { return redirect()->route('dashboard'); });
    Route::get('/appointments', [HomeController::class, 'appointments'])->name('appointments');
    Route::get('/reports', [HomeController::class, 'reports'])->name('reports');
    Route::post('/reports', [HomeController::class, 'storeReport'])->name('reports.store');
    Route::get('/reports/{id}', [HomeController::class, 'getReport'])->name('reports.show');
    Route::put('/reports/{id}', [HomeController::class, 'updateReport'])->name('reports.update');
    Route::get('/reports/{id}/pdf', [HomeController::class, 'downloadPDF'])->name('reports.pdf');
    Route::delete('/reports/{id}', [HomeController::class, 'deleteReport'])->name('reports.delete');

    // Report Signatures
    Route::get('/report-signatures', [HomeController::class, 'reportSignatures'])->name('report-signatures.index');
    Route::post('/report-signatures', [HomeController::class, 'storeReportSignature'])->name('report-signatures.store');
    Route::get('/report-signatures/{id}/image', [HomeController::class, 'reportSignatureImage'])->name('report-signatures.image');
    Route::put('/report-signatures/{id}', [HomeController::class, 'updateReportSignature'])->name('report-signatures.update');
    Route::delete('/report-signatures/{id}', [HomeController::class, 'deleteReportSignature'])->name('report-signatures.delete');

    Route::get('/patients', [HomeController::class, 'patients'])->name('patients');

    // Payments Management
    Route::get('/payments', [HomeController::class, 'payments'])->name('payments');
    Route::post('/payments', [HomeController::class, 'storePayment'])->name('payments.store');
    Route::get('/payments/{id}', [HomeController::class, 'getPayment'])->name('payments.show');
    Route::put('/payments/{id}', [HomeController::class, 'updatePayment'])->name('payments.update');
    Route::delete('/payments/{id}', [HomeController::class, 'deletePayment'])->name('payments.delete');

    // Income Report
    Route::get('/income-report', [HomeController::class, 'incomeReport'])->name('income-report');
    Route::post('/income-report/unlock', [HomeController::class, 'unlockIncomeReport'])->name('income-report.unlock');

    // Daily Collection
    Route::get('/daily-collection', [HomeController::class, 'dailyCollection'])->name('daily-collection');

    // Patient AJAX Routes
    Route::post('/patients/store', [HomeController::class, 'storePatient'])->name('patients.store');
    Route::get('/patients/{id}', [HomeController::class, 'getPatient'])->name('patients.show');
    Route::post('/patients/update/{id}', [HomeController::class, 'updatePatient'])->name('patients.update');
    Route::delete('/patients/{id}', [HomeController::class, 'deletePatient'])->name('patients.delete');

    // Appointment AJAX Routes
    Route::post('/appointments/store', [HomeController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/{id}', [HomeController::class, 'getAppointment'])->name('appointments.show');
    Route::post('/appointments/update/{id}', [HomeController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{id}', [HomeController::class, 'deleteAppointment'])->name('appointments.delete');

    // Lab Test Routes
    Route::post('/lab-tests/store', [HomeController::class, 'storeLabTest'])->name('lab-tests.store');
    Route::get('/lab-tests', [HomeController::class, 'testManagement'])->name('lab-tests.index');
    Route::post('/lab-tests/update/{id}', [HomeController::class, 'updateLabTest'])->name('lab-tests.update');
    Route::delete('/lab-tests/{id}', [HomeController::class, 'deleteLabTest'])->name('lab-tests.delete');

    // Test Parameters (Clinical)
    Route::get('/api/tests', [HomeController::class, 'apiTests'])->name('api.tests');
    Route::post('/tests/quick-store', [HomeController::class, 'quickStoreTest'])->name('tests.quick-store');
    Route::put('/tests/quick-update/{id}', [HomeController::class, 'quickUpdateTest'])->name('tests.quick-update');
    Route::get('/test-parameters', [HomeController::class, 'testParameters'])->name('test-parameters.index');
    Route::post('/test-parameters', [HomeController::class, 'storeTestParameter'])->name('test-parameters.store');

    Route::post('/test-parameters/{id}/intervals', [HomeController::class, 'storeReferenceInterval'])->name('reference-intervals.store');
    Route::get('/test-parameters/{id}/intervals', [HomeController::class, 'getReferenceIntervals'])->name('reference-intervals.get');
    Route::delete('/test-parameters/intervals/{id}', [HomeController::class, 'deleteReferenceInterval'])->name('reference-intervals.delete');
    // Category Routes
    Route::get('/categories', [HomeController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [HomeController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [HomeController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [HomeController::class, 'deleteCategory'])->name('categories.delete');

    // Sub-Category Routes
    Route::get('/sub-categories', [HomeController::class, 'subCategories'])->name('sub-categories.index');
    Route::post('/sub-categories', [HomeController::class, 'storeSubCategory'])->name('sub-categories.store');
    Route::put('/sub-categories/{id}', [HomeController::class, 'updateSubCategory'])->name('sub-categories.update');
    Route::delete('/sub-categories/{id}', [HomeController::class, 'deleteSubCategory'])->name('sub-categories.delete');

    // Master Data
    Route::get('/master-data', [HomeController::class, 'masterData'])->name('master-data.index');
    Route::get('/units', [HomeController::class, 'apiUnits'])->name('units.index');
    Route::post('/units', [HomeController::class, 'storeUnit'])->name('units.store');
    Route::put('/units/{id}', [HomeController::class, 'updateUnit'])->name('units.update');
    Route::delete('/units/{id}', [HomeController::class, 'deleteUnit'])->name('units.delete');
    Route::get('/reference-templates', [HomeController::class, 'apiReferenceTemplates'])->name('reference-templates.index');
    Route::post('/reference-templates', [HomeController::class, 'storeReferenceTemplate'])->name('reference-templates.store');
    Route::put('/reference-templates/{id}', [HomeController::class, 'updateReferenceTemplate'])->name('reference-templates.update');
    Route::delete('/reference-templates/{id}', [HomeController::class, 'deleteReferenceTemplate'])->name('reference-templates.delete');

    Route::get('/result-templates', [HomeController::class, 'apiResultTemplates'])->name('result-templates.index');
    Route::post('/result-templates', [HomeController::class, 'storeResultTemplate'])->name('result-templates.store');
    Route::put('/result-templates/{id}', [HomeController::class, 'updateResultTemplate'])->name('result-templates.update');
    Route::delete('/result-templates/{id}', [HomeController::class, 'deleteResultTemplate'])->name('result-templates.delete');

    Route::get('/flag-templates', [HomeController::class, 'apiFlagTemplates'])->name('flag-templates.index');
    Route::post('/flag-templates', [HomeController::class, 'storeFlagTemplate'])->name('flag-templates.store');
    Route::put('/flag-templates/{id}', [HomeController::class, 'updateFlagTemplate'])->name('flag-templates.update');
    Route::delete('/flag-templates/{id}', [HomeController::class, 'deleteFlagTemplate'])->name('flag-templates.delete');
    Route::post('/reports/update-status/{id}', [HomeController::class, 'updateReportStatus'])->name('reports.update-status');
    Route::get('/dashboard-stats', [HomeController::class, 'getDashboardStats'])->name('dashboard.stats');
    
    // Sidebar Toggle
    Route::post('/sidebar-toggle', [HomeController::class, 'toggleSidebar'])->name('sidebar.toggle');

    // Doctor Routes
    Route::get('/doctors/suggestions', [HomeController::class, 'getDoctorSuggestions'])->name('doctors.suggestions');
    Route::post('/doctors', [HomeController::class, 'storeDoctor'])->name('doctors.store');
    Route::put('/doctors/{id}', [HomeController::class, 'updateDoctor'])->name('doctors.update');
    Route::delete('/doctors/{id}', [HomeController::class, 'deleteDoctor'])->name('doctors.delete');

    // Vital Signs Routes
    Route::get('/vital-signs', [HomeController::class, 'vitalSigns'])->name('vital-signs.index');
    Route::post('/vital-signs/store', [HomeController::class, 'storeVitalSign'])->name('vital-signs.store');
    Route::get('/vital-signs/{id}', [HomeController::class, 'getVitalSign'])->name('vital-signs.show');
    Route::post('/vital-signs/update/{id}', [HomeController::class, 'updateVitalSign'])->name('vital-signs.update');
    Route::delete('/vital-signs/{id}', [HomeController::class, 'deleteVitalSign'])->name('vital-signs.delete');
});


