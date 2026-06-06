@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Test Reports</h4>

				</div>
					<div class="ms-auto w-100 w-md-auto d-flex align-items-center gap-3">
						<a href="{{ route('reports.trash') }}" class="btn btn-light btn-sm w-100 w-md-auto mt-2 mt-md-0 d-flex align-items-center justify-content-center">
							<i class="fa fa-archive me-1"></i> Trash
						</a>
						<button type="button" class="btn btn-primary btn-sm w-100 w-md-auto mt-2 mt-md-0 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#modal-add-report">
							<i class="fa fa-plus-circle me-1"></i> Add New Report
						</button>
					</div>
			</div>
		</div>  

		<!-- Main content -->
		<section class="content">
            <style>
                @media (max-width: 767px) {
                    .table th, .table td { padding: 10px 8px !important; font-size: 11px; }
                    .page-title { font-size: 18px; }
                    .d-flex.gap-2 { flex-direction: column; gap: 5px !important; align-items: flex-end; }
                }

                /* ═══════════════════════════════════════════════
                   PDF VIEWER — PREMIUM 3D GLASSMORPHISM DESIGN
                   ═══════════════════════════════════════════════ */
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

                .pdf-viewer-wrapper {
                    display: flex;
                    flex-direction: column;
                    height: 100vh;
                    height: 100dvh;
                    background: linear-gradient(135deg, #0a0a1a 0%, #0f1729 40%, #0a1628 100%);
                    overflow: hidden;
                    font-family: 'Inter', sans-serif;
                    position: relative;
                }

                /* Animated background orbs */
                .pdf-viewer-wrapper::before {
                    content: '';
                    position: absolute;
                    width: 500px; height: 500px;
                    background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
                    top: -100px; left: -100px;
                    border-radius: 50%;
                    pointer-events: none;
                    animation: orbFloat 8s ease-in-out infinite;
                }
                .pdf-viewer-wrapper::after {
                    content: '';
                    position: absolute;
                    width: 400px; height: 400px;
                    background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%);
                    bottom: 50px; right: -80px;
                    border-radius: 50%;
                    pointer-events: none;
                    animation: orbFloat 10s ease-in-out infinite reverse;
                }
                @keyframes orbFloat {
                    0%,100% { transform: translate(0,0) scale(1); }
                    50% { transform: translate(30px,20px) scale(1.05); }
                }

                /* ── TOOLBAR ───────────────────────────────────── */
                .pdf-toolbar {
                    background: linear-gradient(180deg, rgba(15,23,42,0.98) 0%, rgba(10,15,30,0.95) 100%);
                    backdrop-filter: blur(25px);
                    -webkit-backdrop-filter: blur(25px);
                    border-bottom: 1px solid rgba(255,255,255,0.08);
                    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
                    color: #e2e8f0;
                    padding: 0 24px;
                    height: 64px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    z-index: 1000;
                    user-select: none;
                    position: relative;
                }

                .toolbar-section {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                /* Filename area */
                #viewer-filename-link {
                    font-size: 13px;
                    font-weight: 600;
                    color: #94a3b8 !important;
                    letter-spacing: 0.3px;
                    transition: color 0.2s;
                    max-width: 220px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                #viewer-filename-link:hover { color: #6366f1 !important; }

                /* Clear Vertical Divider (Gaps instead of lines) */
                .toolbar-divider {
                    width: 0px;
                    height: 24px;
                    background: transparent;
                    margin: 0 16px;
                }

                /* ── CENTER ZOOM GROUP ─────────────────────────── */
                .pdf-toolbar-center {
                    position: absolute;
                    left: 50%;
                    transform: translateX(-50%);
                    display: flex;
                    align-items: center;
                    background: rgba(15,23,42,0.6);
                    border: 1px solid rgba(255,255,255,0.1);
                    border-radius: 14px;
                    padding: 4px 8px;
                    gap: 8px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
                }

                /* ── TOOLBAR BUTTONS ───────────────────────────── */
                .pdf-btn {
                    background: transparent;
                    border: 1px solid transparent;
                    color: #94a3b8;
                    padding: 8px 12px;
                    border-radius: 10px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    font-size: 13px;
                    font-weight: 600;
                    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                    position: relative;
                    outline: none !important;
                }
                .pdf-btn i { font-size: 16px; }
                .pdf-btn:hover {
                    color: #fff;
                    background: rgba(255,255,255,0.08);
                    transform: translateY(-1px);
                }
                .pdf-btn:active { transform: translateY(0); }

                /* Action buttons (download/share/print) */
                .pdf-btn.pdf-btn-action {
                    background: rgba(99,102,241,0.12);
                    color: #a5b4fc;
                    border: 1px solid rgba(99,102,241,0.2);
                }
                .pdf-btn.pdf-btn-action:hover {
                    background: rgba(99,102,241,0.25);
                    color: #fff;
                    border-color: rgba(99,102,241,0.4);
                    box-shadow: 0 0 15px rgba(99,102,241,0.3);
                }

                .pdf-btn.pdf-btn-share {
                    background: rgba(16,185,129,0.12);
                    color: #6ee7b7;
                    border: 1px solid rgba(16,185,129,0.2);
                }
                .pdf-btn.pdf-btn-share:hover {
                    background: rgba(16,185,129,0.25);
                    color: #fff;
                    border-color: rgba(16,185,129,0.4);
                    box-shadow: 0 0 15px rgba(16,185,129,0.3);
                }

                /* Zoom level badge */
                .zoom-level {
                    font-size: 13px;
                    min-width: 50px;
                    text-align: center;
                    font-weight: 700;
                    color: #fff;
                    background: rgba(255,255,255,0.05);
                    border-radius: 8px;
                    padding: 4px 10px;
                }

                /* ── HEADER TOGGLE (Segmented Control) ────────── */
                .header-toggle-container {
                    display: flex;
                    align-items: center;
                    background: rgba(0,0,0,0.4);
                    border: 1px solid rgba(255,255,255,0.1);
                    border-radius: 12px;
                    padding: 4px;
                    gap: 4px;
                }
                .pdf-header-toggle {
                    font-size: 12px !important;
                    padding: 8px 16px !important;
                    border-radius: 10px !important;
                    border: none !important;
                    color: #64748b !important;
                    font-weight: 600;
                    transition: all 0.3s ease !important;
                    background: transparent !important;
                }
                .pdf-header-toggle.is-active {
                    background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
                    color: #fff !important;
                    box-shadow: 0 4px 12px rgba(79,70,229,0.4);
                }
                .dropdown-item.pdf-header-toggle.is-active {
                    background: rgba(99,102,241,0.2) !important;
                    color: #a5b4fc !important;
                    border-left: 3px solid #6366f1 !important;
                    box-shadow: none !important;
                }
                .dropdown-item.pdf-header-toggle:hover:not(.is-active) {
                    background: rgba(255,255,255,0.08) !important;
                    color: #fff !important;
                }

                .test-item-row {
                    transition: all 0.2s ease;
                }
                .test-item-row:hover {
                    background: rgba(99,102,241,0.03) !important;
                }
                @media (max-width: 767px) {
                    .test-item-row {
                        background: #f8fafc !important;
                        border: 1px solid #e2e8f0 !important;
                        padding: 15px !important;
                        border-radius: 12px !important;
                        margin-bottom: 15px !important;
                    }
                    .test-item-row .col-12 {
                        padding: 5px 0;
                    }
                    .test-item-row label {
                        font-size: 11px;
                        text-transform: uppercase;
                        color: #64748b;
                        font-weight: 700;
                        margin-bottom: 4px;
                        display: block;
                    }
                }

                /* ── VIEWPORT ──────────────────────────────────── */
                .pdf-viewport {
                    flex: 1;
                    overflow: auto;
                    padding: 36px 24px;
                    display: block;
                    scroll-behavior: smooth;
                    scrollbar-width: thin;
                    scrollbar-color: rgba(99,102,241,0.4) transparent;
                }
                .pdf-viewport::-webkit-scrollbar { width: 8px; }
                .pdf-viewport::-webkit-scrollbar-track { background: transparent; }
                .pdf-viewport::-webkit-scrollbar-thumb {
                    background: rgba(99,102,241,0.35);
                    border-radius: 8px;
                    border: 2px solid transparent;
                    background-clip: content-box;
                }
                .pdf-viewport::-webkit-scrollbar-thumb:hover {
                    background: rgba(99,102,241,0.6);
                    background-clip: content-box;
                }

                /* ── PDF PAGE (3D shadow lift) ─────────────────── */
                .pdf-page-container {
                    width: max-content;
                    max-width: none;
                    margin: 0 auto;
                    transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                }
                #pdf-canvas-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 24px;
                    width: max-content;
                    max-width: none;
                }
                .pdf-canvas-wrapper {
                    line-height: 0;
                    border-radius: 4px;
                    overflow: hidden;
                    box-shadow:
                        0 0 0 1px rgba(255,255,255,0.08),
                        0 20px 60px rgba(0,0,0,0.8),
                        0 8px 24px rgba(0,0,0,0.6),
                        0 2px 8px rgba(0,0,0,0.4);
                    transition: box-shadow 0.3s ease;
                }
                .pdf-canvas-wrapper canvas {
                    display: block;
                    height: auto;
                }
                  @media (max-width: 991px) {
                    .pdf-toolbar { height: auto; padding: 16px; flex-direction: column; gap: 16px; background: rgba(15,23,42,1); }
                    .pdf-toolbar-center { position: static; transform: none; width: 100%; justify-content: center; margin: 5px 0; background: rgba(255,255,255,0.05); }
                    .toolbar-section { width: 100%; justify-content: space-between; gap: 10px; }
                    .toolbar-section .d-flex { gap: 8px !important; }
                    #viewer-filename-link { max-width: 130px; font-size: 12px; }
                    .pdf-viewport { padding: 10px 5px; background: #0f172a; }
                    .pdf-page-container { margin: 0 auto; }
                    .pdf-canvas-wrapper { margin-bottom: 10px; }
                    canvas { height: auto !important; }
                    .pdf-btn span { font-size: 11px; }
                    .pdf-btn { padding: 8px 10px; }
                    
                    /* Reports Table Mobile Optimization */
                    .card-table thead { display: none; }
                    .card-table tbody tr { display: block; border: 1px solid rgba(0,0,0,0.05); margin-bottom: 20px; border-radius: 16px; padding: 20px; background: #fff !important; box-shadow: 0 4px 15px rgba(0,0,0,0.06); position: relative; }
                    .card-table tbody td { display: flex; justify-content: space-between; align-items: center; border: none !important; padding: 10px 0 !important; text-align: right; border-bottom: 1px dashed #f1f5f9 !important; }
                    .card-table tbody td:last-child { border-bottom: none !important; }
                    .card-table tbody td::before { content: attr(data-label); font-weight: 700; text-align: left; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
                    .card-table .text-end { justify-content: center; width: 100%; border-top: 1px solid #f1f5f9 !important; margin-top: 15px; padding-top: 20px !important; gap: 12px; flex-wrap: wrap; }
                    .card-table .btn { flex: 1; min-width: 80px; height: 42px; border-radius: 10px; }
                    .card-table .fw-600.text-primary { font-size: 16px; color: #4f46e5 !important; }
                }

                @media (max-width: 480px) {
                    .toolbar-section { flex-wrap: wrap; justify-content: center; gap: 12px; }
                    .pdf-btn span { display: none; }
                    #viewer-filename-link { display: none; }
                    .toolbar-divider { margin: 0 4px; }
                }

                @media print {
                    @page { 
                        size: A4 portrait;
                        margin: 0; 
                    }
                    body { margin: 0; padding: 0; background: white !important; -webkit-print-color-adjust: exact; }
                    body * { visibility: hidden; }
                    .pdf-viewer-wrapper, .pdf-viewport, #pdf-page-container, #pdf-canvas-container, #pdf-canvas-container *, canvas { visibility: visible !important; }
                    .pdf-viewer-wrapper { position: absolute; left: 0; top: 0; width: 100%; height: auto; background: white !important; overflow: visible !important; display: block !important; }
                    .pdf-viewport { padding: 0 !important; margin: 0 !important; overflow: visible !important; display: block !important; }
                    #pdf-page-container { transform: none !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
                    #pdf-canvas-container { width: 100% !important; gap: 0 !important; }
                    .pdf-canvas-wrapper { box-shadow: none !important; margin-bottom: 0 !important; page-break-after: always; width: 100% !important; height: auto !important; }
                    canvas { width: 100% !important; height: auto !important; display: block !important; }
                    .pdf-toolbar, .modal-header, .modal-footer, .btn { display: none !important; }
                }

                .report-header { border-bottom: 3px solid #0056b3; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-start; }
                .report-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
                .report-table th { border-bottom: 2px solid #333; padding: 12px; text-align: left; background: #f9f9f9; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #555; }
                .report-table td { border-bottom: 1px solid #eee; padding: 12px; text-align: left; font-size: 13px; }
                .report-section-title { font-weight: 800; color: #0056b3; padding: 15px 0 5px 0 !important; border-bottom: none !important; font-size: 14px; text-transform: uppercase; }
                .barcode-container { text-align: center; }
                .barcode-bars { font-family: 'Libre Barcode 39', cursive; font-size: 40px; line-height: 1; color: #000; }
                .patient-info-box { background: #fcfcfc; border: 1px solid #eee; border-radius: 8px; padding: 20px; margin-bottom: 30px; }
                .patient-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 40px; }
                .info-item { font-size: 13px; line-height: 1.6; }
                .info-label { color: #777; font-weight: 500; min-width: 100px; display: inline-block; }
                .info-value { color: #000; font-weight: 600; }
                .report-title-main { font-size: 28px; font-weight: 900; color: #000; letter-spacing: -0.5px; }
                .signature-section { margin-top: 50px; display: flex; justify-content: space-between; }
                .signature-box { text-align: center; border-top: 1px solid #333; width: 200px; padding-top: 5px; font-size: 12px; font-weight: 600; }

            </style>
            <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">

			<div class="row">				
				<div class="col-12">
				  <div class="box">
					<div class="box-body">
						<div class="row mb-3">
							<div class="col-md-4">
								<div class="input-group">
									<span class="input-group-text bg-primary-light border-primary-light"><i class="fa fa-search text-primary"></i></span>
									<input type="text" id="report-search" class="form-control" placeholder="Search reports..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
								</div>
							</div>
						</div>
						<div class="table-responsive rounded card-table">
							<table class="table border-no table-striped" id="report-table">
								<thead>
									<tr>
										<th class="d-none d-md-table-cell">ID</th>
										<th>Patient Name</th>
										<th class="d-none d-lg-table-cell">Doctor Name</th>
										<th>Date Released</th>
										<th class="text-end">Action</th>
									</tr>
								</thead>
								<tbody>
                                     @forelse($reports as $report)
									<tr>
										<td class="fw-600 text-primary d-none d-md-table-cell">#{{ $report->id }}</td>
										<td class="fw-600" data-label="Patient">{{ $report->patient->first_name }} {{ $report->patient->last_name }}</td>
										<td class="d-none d-lg-table-cell text-fade" data-label="Doctor">{{ $report->doctor_name }}</td>
										<td class="fs-12" data-label="Date">{{ \Carbon\Carbon::parse($report->report_released_on)->format('d-M-Y') }}</td>
										<td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-info-light btn-sm btn-view d-flex align-items-center justify-content-center" data-id="{{ $report->id }}" data-bs-toggle="modal" data-bs-target="#modal-view-report" title="View / PDF" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-file-pdf-o"></i></button>
                                                <button class="btn btn-primary-light btn-sm btn-edit d-flex align-items-center justify-content-center" data-id="{{ $report->id }}" data-bs-toggle="modal" data-bs-target="#modal-edit-report" title="Edit Report" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger-light btn-sm btn-delete d-flex align-items-center justify-content-center" data-id="{{ $report->id }}" title="Delete" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-trash"></i></button>
                                            </div>
										</td>
									</tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-50">
                                            <i class="fa fa-file-text-o fa-3x text-fade d-block mb-10"></i>
                                            <span class="text-fade fs-18">No test reports generated yet.</span>
                                        </td>
                                    </tr>
                                    @endforelse
								</tbody>
							  </table>
						</div>				
					</div>
				  </div>
				</div>
			</div>
		</section>
	  </div>
  </div>

  <!-- Add Report Modal -->
  <div class="modal center-modal fade" id="modal-add-report" tabindex="-1">
	  <div class="modal-dialog modal-xl">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Generate Lab Report</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-report">
                @csrf
				<h4 class="text-primary border-bottom pb-2 mb-3">General Information</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Select Patient</label>
							<select class="form-select" name="patient_id" id="add-patient-id" required>
								<option value="">-- Select Patient --</option>
								@foreach($patients as $patient)
										<option value="{{ $patient->id }}" data-gender="{{ $patient->gender }}" data-age="{{ $patient->age }}">{{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Referring Doctor</label>
							<div class="input-group flex-nowrap">
								<select class="form-select report-doctor-select" name="doctor_name" id="add-report-doctor" required>
									<option value="">-- Select Doctor --</option>
								</select>
								<button type="button" class="btn btn-success btn-add-report-doctor" title="Add New Doctor"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-warning btn-edit-report-doctor" title="Edit Selected"><i class="fa fa-edit"></i></button>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Sample Received On</label>
							<input type="datetime-local" class="form-control" name="sample_received_on" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Report Released On</label>
							<input type="datetime-local" class="form-control" name="report_released_on" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Report Notes</label>
							<textarea class="form-control" name="notes" rows="3" placeholder="Notes to show in the PDF"></textarea>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label">Authorized Signature</label>
							<select class="form-select report-signature-select" name="report_signature_id">
								<option value="">No signature</option>
								@foreach($signatures as $signature)
									<option value="{{ $signature->id }}">{{ $signature->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="form-label">PIN</label>
							<input type="password" class="form-control signature-pin-input" name="signature_pin" autocomplete="new-password" placeholder="PIN">
						</div>
					</div>
				</div>

                <div class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2">
                    <h4 class="text-primary mb-0">Dynamic Test Results</h4>
                    <button type="button" class="btn btn-sm btn-success" id="btn-add-test-row"><i class="fa fa-plus me-1"></i> Add Test Item</button>
                </div>
				
                <div class="d-none d-md-flex row fw-bold text-muted mb-2 px-3">
                    <div class="col-md-2">Main Heading</div>
                    <div class="col-md-2">Subheading</div>
                    <div class="col-md-2">Parameter</div>
                    <div class="col-md-1">Observed</div>
                    <div class="col-md-1">Unit</div>
                    <div class="col-md-3">Reference Value</div>
                    <div class="col-md-1 text-center">Action</div>
                </div>

                <div id="dynamic-tests-container">
                    <!-- Dynamic rows go here -->
                    <div class="row test-item-row align-items-center mb-3 pb-3 border-bottom bg-light-xs p-10 rounded shadow-sm">
                        <div class="col-md-2 col-12 mb-md-0 mb-3">
                            <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Main Heading</div>
                            <select class="form-select border-primary-light" name="test_category[]">
                                <option value="">Main Heading</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12 mb-md-0 mb-3 px-md-1">
                            <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Subheading</div>
                            <select class="form-select border-primary-light" name="test_subcategory[]">
                                <option value="">None</option>
                                @foreach($subCategories as $sub)
                                    <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12 mb-md-0 mb-3">
                            <div class="d-md-none fw-bold fs-11 text-uppercase text-primary mb-1">Parameter</div>
                            <select class="form-select test-selector-dynamic border-primary shadow-none" name="test_name[]" required>
                                <option value="">-- Select Test --</option>
                                @foreach($tests as $test)
                                    <option value="{{ $test->name }}" 
                                        data-unit="{{ $test->parameter->unit ?? '' }}"
                                        data-male-ref="{{ $test->parameter->male_reference ?? '' }}"
                                        data-female-ref="{{ $test->parameter->female_reference ?? '' }}"
                                        data-male-min="{{ $test->parameter->male_min ?? '' }}"
                                        data-male-max="{{ $test->parameter->male_max ?? '' }}"
                                        data-female-min="{{ $test->parameter->female_min ?? '' }}"
	                                        data-female-max="{{ $test->parameter->female_max ?? '' }}"
	                                        data-critical-low="{{ $test->parameter->critical_low ?? '' }}"
	                                        data-critical-high="{{ $test->parameter->critical_high ?? '' }}"
		                                        data-reference-intervals="{{ $test->referenceIntervals->map->only(['gender', 'age_min', 'age_max', 'reference_text', 'min_value', 'max_value'])->values()->toJson() }}"
	                                        data-is-immunoassay="{{ $test->parameter->is_immunoassay ?? 0 }}"
                                        data-bio-ref="{{ $test->parameter->biological_reference ?? '' }}"
                                        data-normal="{{ $test->description }}">{{ $test->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 col-6 mb-md-0 mb-2 px-1">
                            <div class="d-md-none fw-bold fs-12 mb-1 text-success">Observed</div>
                            <input type="text" class="form-control observed-value-input" name="observed_value[]" list="list-observed" placeholder="Observed" required>
                        </div>
                        <div class="col-md-1 col-6 mb-md-0 mb-2 px-1">
                            <div class="d-md-none fw-bold fs-12 mb-1 text-muted">Unit</div>
                            <select class="form-select" name="test_unit[]">
                                <option value="">Unit</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->name }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-12 mb-md-0 mb-2 px-1">
                            <div class="d-md-none fw-bold fs-12 mb-1 text-muted">Reference Value</div>
                            <input type="text" class="form-control normal-val-dynamic" name="normal_value[]" placeholder="Reference Interval">
                            <input type="hidden" class="bio-val-dynamic" name="biological_reference[]" value="">
                            <input type="hidden" class="flag-selector" name="test_flag[]" value="">
                        </div>
                        <div class="col-md-1 col-2 text-center pt-md-0 pt-20">
                            <button type="button" class="btn btn-danger-light btn-sm remove-row mt-md-0 mt-2" title="Remove Test"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-save-report">Generate Report</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Report Modal -->
  <div class="modal center-modal fade" id="modal-edit-report" tabindex="-1">
	  <div class="modal-dialog modal-xl">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Lab Report</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-report">
                @csrf
                <input type="hidden" name="id" id="edit-report-id">
				<h4 class="text-primary border-bottom pb-2 mb-3">General Information</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Select Patient</label>
							<select class="form-select" name="patient_id" id="edit-patient-id" required>
								<option value="">-- Select Patient --</option>
								@foreach($patients as $patient)
										<option value="{{ $patient->id }}" data-gender="{{ $patient->gender }}" data-age="{{ $patient->age }}">{{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Referring Doctor</label>
							<div class="input-group flex-nowrap">
								<select class="form-select report-doctor-select" name="doctor_name" id="edit-report-doctor" required>
									<option value="">-- Select Doctor --</option>
								</select>
								<button type="button" class="btn btn-success btn-add-report-doctor" title="Add New Doctor"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-warning btn-edit-report-doctor" title="Edit Selected"><i class="fa fa-edit"></i></button>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Sample Received On</label>
							<input type="datetime-local" class="form-control" name="sample_received_on" id="edit-sample-date" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Report Released On</label>
							<input type="datetime-local" class="form-control" name="report_released_on" id="edit-release-date" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Report Notes</label>
							<textarea class="form-control" name="notes" id="edit-report-notes" rows="3" placeholder="Notes to show in the PDF"></textarea>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label">Authorized Signature</label>
							<select class="form-select report-signature-select" name="report_signature_id" id="edit-report-signature-id">
								<option value="">No signature</option>
								@foreach($signatures as $signature)
									<option value="{{ $signature->id }}">{{ $signature->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="form-label">PIN</label>
							<input type="password" class="form-control signature-pin-input" name="signature_pin" id="edit-signature-pin" autocomplete="new-password" placeholder="Required">
						</div>
					</div>
				</div>

                <div class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2">
                    <h4 class="text-primary mb-0">Dynamic Test Results</h4>
                    <button type="button" class="btn btn-sm btn-success" id="btn-add-edit-test-row"><i class="fa fa-plus me-1"></i> Add Test Item</button>
                </div>
				
                <div class="d-none d-md-flex row fw-bold text-muted mb-2 px-3">
                    <div class="col-md-2">Main Heading</div>
                    <div class="col-md-2">Subheading</div>
                    <div class="col-md-2">Parameter</div>
                    <div class="col-md-1">Observed</div>
                    <div class="col-md-1">Unit</div>
                    <div class="col-md-3">Reference Value</div>
                    <div class="col-md-1 text-center">Action</div>
                </div>

                <div id="edit-dynamic-tests-container">
                    <!-- Populated via JS -->
                </div>

                <datalist id="list-observed">
                    @foreach($templates as $tmpl)
                        <option value="{{ $tmpl->name }}">
                    @endforeach
                </datalist>

                <datalist id="list-units">
                    @foreach($units as $u)
                        <option value="{{ $u->name }}">
                    @endforeach
                </datalist>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-update-report">Update Report</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Premium PDF Viewer Modal -->
  <div class="modal fade" id="modal-view-report" tabindex="-1">
	  <div class="modal-dialog modal-fullscreen">
		<div class="modal-content border-0">
		  <div class="modal-body p-0">
              <div class="pdf-viewer-wrapper">

                  <!-- Premium Toolbar -->
                  <div class="pdf-toolbar d-print-none">

                      <!-- LEFT: Back + Filename -->
                      <div class="toolbar-section">
                          <button type="button" class="pdf-btn pdf-btn-close" data-bs-dismiss="modal" title="Close Viewer">
                              <i class="fa fa-arrow-left"></i>
                          </button>
                          <div class="toolbar-divider"></div>
                          <div class="d-flex align-items-center gap-2">
                              <div style="width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                                  <i class="fa fa-file-pdf-o" style="font-size:14px;color:#fff;"></i>
                              </div>
                              <a href="javascript:void(0)" id="viewer-filename-link" title="Open in New Tab">report_preview.pdf</a>
                          </div>
                      </div>

                      <!-- CENTER: Zoom Controls -->
                      <div class="pdf-toolbar-center">
                          <button type="button" class="pdf-btn" id="zoom-out" title="Zoom Out"><i class="fa fa-minus"></i></button>
                          <span class="zoom-level" id="zoom-text">100%</span>
                          <button type="button" class="pdf-btn" id="zoom-in" title="Zoom In"><i class="fa fa-plus"></i></button>
                          <div class="toolbar-divider" style="height: 18px; margin: 0 4px;"></div>
                          <button type="button" class="pdf-btn" id="fit-width" title="Fit Width"><i class="fa fa-arrows-h"></i></button>
                      </div>

                      <!-- RIGHT: Mode Selector + Actions -->
                      <div class="toolbar-section">
                          <!-- Mode Selector Dropdown -->
                          <div class="dropdown">
                              <button type="button" class="pdf-btn pdf-btn-action dropdown-toggle" id="btn-mode-selector" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="fa fa-id-card-o" id="current-mode-icon"></i>
                                  <span class="d-none d-lg-inline ml-2" id="current-mode-text">With Header</span>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-0 shadow-lg" style="background: rgba(15,23,42,0.98); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 12px; margin-top: 10px;">
                                  <li>
                                      <a class="dropdown-item py-10 px-20 pdf-header-toggle is-active" href="javascript:void(0)" id="btn-with-header">
                                          <i class="fa fa-id-card-o me-2"></i> With Lab Header
                                      </a>
                                  </li>
                                  <li>
                                      <a class="dropdown-item py-10 px-20 pdf-header-toggle" href="javascript:void(0)" id="btn-without-header">
                                          <i class="fa fa-file-o me-2"></i> No Header (Plain)
                                      </a>
                                  </li>
                              </ul>
                          </div>

                          <div class="toolbar-divider" style="height: 32px; background: rgba(255,255,255,0.15);"></div>

                          <div class="d-flex align-items-center gap-2">
                              <button type="button" class="pdf-btn pdf-btn-action" id="btn-viewer-fullscreen" title="Toggle Fullscreen">
                                  <i class="fa fa-expand"></i><span class="d-none d-xl-inline">Full Screen</span>
                              </button>
                              <button type="button" class="pdf-btn pdf-btn-action" id="btn-viewer-print" title="Print Report">
                                  <i class="fa fa-print"></i><span class="d-none d-xl-inline">Print</span>
                              </button>
                              <button type="button" class="pdf-btn pdf-btn-action" id="btn-viewer-download" title="Download PDF">
                                  <i class="fa fa-download"></i><span class="d-none d-xl-inline">Download</span>
                              </button>
                              <button type="button" class="pdf-btn pdf-btn-share" id="btn-viewer-share" title="Share via WhatsApp/Web">
                                  <i class="fa fa-share-alt"></i><span class="d-none d-xl-inline">Share</span>
                              </button>
                          </div>
                      </div>
                  </div>

                  <!-- Viewport -->
                  <div class="pdf-viewport" id="pdf-viewport">
                      <div class="pdf-page-container" id="pdf-page-container">
                          <div id="pdf-canvas-container">
                              <div class="text-center py-50" style="color:#64748b;">
                                  <div style="width:56px;height:56px;border:3px solid rgba(99,102,241,0.3);border-top-color:#6366f1;border-radius:50%;animation:spin 0.9s linear infinite;margin:0 auto 20px;"></div>
                                  <p style="font-size:14px;letter-spacing:0.5px;">Initializing PDF Engine...</p>
                              </div>
                          </div>
                      </div>
                  </div>

              </div>
		  </div>
		</div>
	  </div>
  </div>
  <style>@keyframes spin{to{transform:rotate(360deg)}}</style>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <script>
      // Initialize PDF.js
      var pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;
      if (pdfjsLib) {
          pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
          window.pdfjsLib = pdfjsLib;
      }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

  <!-- Datalist for Auto-complete -->
  <datalist id="standard-tests-list">
      <option value="Blood Glucose (Fasting)">
      <option value="Total Cholesterol">
      <option value="Triglycerides">
      <option value="HDL Cholesterol">
      <option value="LDL Cholesterol">
      <option value="VLDL">
      <option value="Cholesterol / HDL Ratio">
      <option value="LDL / HDL Ratio">
  </datalist>

  <script>
	  $(document).ready(function() {
		  $.ajaxSetup({
			  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		  });

		  // Live Search for Reports
		  $("#report-search").on("keyup", function() {
			  var value = $(this).val().toLowerCase();
			  $("#report-table tbody tr").filter(function() {
				  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			  });
		  });

          // Standard Tests Dictionary for Auto-Fill
          const standardTests = {
              "Blood Glucose (Fasting)": { category: "BIOCHEMISTRY", normal: "60 - 110 mg/dl" },
              "Total Cholesterol": { category: "LIPID PROFILE (FASTING)", normal: "130 - 220 mg/dl" },
              "Triglycerides": { category: "LIPID PROFILE (FASTING)", normal: "40 - 170 mg/dl" },
              "HDL Cholesterol": { category: "LIPID PROFILE (FASTING)", normal: "30 - 70 mg/dl" },
              "LDL Cholesterol": { category: "LIPID PROFILE (FASTING)", normal: "60 - 160 mg/dl" },
              "VLDL": { category: "LIPID PROFILE (FASTING)", normal: "8 - 32 mg/dl" },
              "Cholesterol / HDL Ratio": { category: "LIPID PROFILE (FASTING)", normal: "< 4" },
              "LDL / HDL Ratio": { category: "LIPID PROFILE (FASTING)", normal: "< 3.5" }
          };

          const REPORT_HEADER_IMAGE = "data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/report-header-awwal.png'))) }}";
          const REPORT_FOOTER_IMAGE = "data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/report-footer-awwal.png'))) }}";

          // Dynamic Rows Logic (Refined Alignment & Auto-fill)
          const trTemplate = `
            <div class="row test-item-row align-items-center mb-3 pb-3 border-bottom bg-light-xs p-10 rounded">
                <div class="col-md-2 col-12 mb-md-0 mb-2">
                    <div class="d-md-none fw-bold fs-12 mb-1 text-muted">Main Heading</div>
                    <select class="form-select" name="test_category[]">
                        <option value="">Main Heading</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-12 mb-md-0 mb-2 px-1">
                    <div class="d-md-none fw-bold fs-12 mb-1 text-muted">Subheading</div>
                    <select class="form-select" name="test_subcategory[]">
                        <option value="">None</option>
                        @foreach($subCategories as $sub)
                            <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-12 mb-md-0 mb-2">
                    <div class="d-md-none fw-bold fs-12 mb-1 text-primary">Parameter</div>
                    <select class="form-select test-selector-dynamic" name="test_name[]" required>
                        <option value="">-- Select Test --</option>
                        @foreach($tests as $test)
                            <option value="{{ $test->name }}" 
                                data-unit="{{ $test->parameter->unit ?? '' }}"
                                data-male-ref="{{ $test->parameter->male_reference ?? '' }}"
                                data-female-ref="{{ $test->parameter->female_reference ?? '' }}"
                                data-male-min="{{ $test->parameter->male_min ?? '' }}"
                                data-male-max="{{ $test->parameter->male_max ?? '' }}"
                                data-female-min="{{ $test->parameter->female_min ?? '' }}"
                                data-female-max="{{ $test->parameter->female_max ?? '' }}"
                                data-critical-low="{{ $test->parameter->critical_low ?? '' }}"
                                data-critical-high="{{ $test->parameter->critical_high ?? '' }}"
                                data-reference-intervals="{{ $test->referenceIntervals->map->only(['gender', 'age_min', 'age_max', 'reference_text', 'min_value', 'max_value'])->values()->toJson() }}"
                                data-is-immunoassay="{{ $test->parameter->is_immunoassay ?? 0 }}"
                                data-bio-ref="{{ $test->parameter->biological_reference ?? '' }}"
                                data-normal="{{ $test->description }}">{{ $test->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 col-6 mb-md-0 mb-2 px-1">
                    <div class="d-md-none fw-bold fs-12 mb-1 text-success">Observed</div>
                    <input type="text" class="form-control observed-value-input" name="observed_value[]" list="list-observed" placeholder="Observed" required>
                </div>
                <div class="col-md-1 col-6 mb-md-0 mb-2 px-1">
                    <div class="d-md-none fw-bold fs-12 mb-1 text-muted">Unit</div>
                    <select class="form-select" name="test_unit[]">
                        <option value="">Unit</option>
                        @foreach($units as $u)
                            <option value="{{ $u->name }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-12 mb-md-0 mb-3 px-md-1">
                    <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Reference Value</div>
                    <input type="text" class="form-control normal-val-dynamic border-info-light" name="normal_value[]" placeholder="Reference Interval">
                    <input type="hidden" class="bio-val-dynamic" name="biological_reference[]" value="">
	                    <input type="hidden" class="flag-selector" name="test_flag[]" value="">
                </div>
                <div class="col-md-1 col-2 text-center pt-md-0 pt-20">
                    <button type="button" class="btn btn-danger-light btn-sm remove-row mt-md-0 mt-2" title="Remove Test"><i class="fa fa-trash"></i></button>
                </div>
            </div>`;

          // Auto-fill Normal Value from Select & Handle Auto-calc Logic
          $(document).on('change', '.test-selector-dynamic', function() {
              let selected = $(this).find(':selected');
	              let row = $(this).closest('.test-item-row');
	              let modal = $(this).closest('.modal');
	              let gender = modal.find('select[name="patient_id"] :selected').data('gender') || 'M/F';
	              let age = parseInt(modal.find('select[name="patient_id"] :selected').data('age'), 10);
	              
	              // Set Unit
	              row.find('select[name="test_unit[]"]').val(selected.data('unit') || '');
	              
	              // Set Reference Interval based on Gender
	              let ageInterval = getMatchingReferenceInterval(selected, gender, age);
	              let ref = ageInterval ? ageInterval.reference_text : (gender === 'Male' ? selected.data('male-ref') : selected.data('female-ref'));
	              if (!ref) ref = selected.data('male-ref') || selected.data('female-ref') || selected.data('normal') || '';
              
              row.find('.normal-val-dynamic').val(ref);
              
              // Keep legacy JSON field filled for older reports while printing the normal value column.
              let masterBio = selected.data('bio-ref');
              row.find('.bio-val-dynamic').val(masterBio || ref);
              
              // Trigger calculation if value exists
              row.find('.observed-value-input').trigger('input');
          });

          // Keep hidden flag values compatible with older saved reports.
          $(document).on('input', '.observed-value-input', function() {
              let val = parseFloat($(this).val());
	              let row = $(this).closest('.test-item-row');
	              let selected = row.find('.test-selector-dynamic :selected');
	              let modal = $(this).closest('.modal');
	              let gender = modal.find('select[name="patient_id"] :selected').data('gender') || 'Male';
	              let age = parseInt(modal.find('select[name="patient_id"] :selected').data('age'), 10);
	              let flagSelector = row.find('.flag-selector');
              
              if (isNaN(val)) {
                  flagSelector.val('');
                  return;
              }

	              let isImmuno = selected.data('is-immunoassay') == 1;
	              let criticalLow = parseFloat(selected.data('critical-low'));
	              let criticalHigh = parseFloat(selected.data('critical-high'));
	              let ageInterval = getMatchingReferenceInterval(selected, gender, age);
	              let min = ageInterval ? ageInterval.min_value : (gender === 'Male' ? selected.data('male-min') : selected.data('female-min'));
	              let max = ageInterval ? ageInterval.max_value : (gender === 'Male' ? selected.data('male-max') : selected.data('female-max'));

	              if (!isNaN(criticalLow) && val <= criticalLow) {
	                  flagSelector.val('C');
	                  return;
	              }

	              if (!isNaN(criticalHigh) && val >= criticalHigh) {
	                  flagSelector.val('C');
	                  return;
	              }

              if (isImmuno) {
                  // Immunoassay logic: <0.9 Neg, 0.9-1.1 Bord, >1.1 Pos
                  if (val < 0.9) flagSelector.val('N');
                  else if (val >= 0.9 && val <= 1.1) flagSelector.val('B');
                  else if (val > 1.1) flagSelector.val('P');
              } else if (min !== undefined && max !== undefined) {
                  // Standard Min-Max logic
	                  if (val < min) flagSelector.val('L');
	                  else if (val > max) flagSelector.val('H');
	                  else flagSelector.val('N');
	              }
	          });

	          function getMatchingReferenceInterval(selected, gender, age) {
	              let intervals = selected.data('reference-intervals') || [];
	              if (!Array.isArray(intervals) || isNaN(age)) return null;
	              gender = (gender || '').toLowerCase();
	              return intervals
	                  .filter(interval => {
	                      let intervalGender = (interval.gender || '').toLowerCase();
	                      let min = interval.age_min === null ? 0 : parseInt(interval.age_min, 10);
	                      let max = interval.age_max === null ? 999 : parseInt(interval.age_max, 10);
	                      return (!intervalGender || intervalGender === gender) && age >= min && age <= max;
	                  })
	                  .sort((a, b) => parseInt(b.age_min || 0, 10) - parseInt(a.age_min || 0, 10))[0] || null;
	          }

          // Re-trigger calculation when patient (gender) changes
          $(document).on('change', 'select[name="patient_id"]', function() {
              let modal = $(this).closest('.modal');
              modal.find('.test-selector-dynamic').trigger('change');
          });

          function updateSignaturePinRequirement(select) {
              const modal = $(select).closest('.modal');
              const pinInput = modal.find('.signature-pin-input');
              const hasSignature = !!$(select).val();
              pinInput.prop('required', hasSignature);
              pinInput.attr('placeholder', hasSignature ? 'Required' : 'PIN');
              if (!hasSignature) {
                  pinInput.val('');
              }
          }

          $(document).on('change', '.report-signature-select', function() {
              updateSignaturePinRequirement(this);
          });

          $('.report-signature-select').each(function() {
              updateSignaturePinRequirement(this);
          });

          $('#btn-add-test-row, #btn-add-edit-test-row').click(function() {
              let target = $(this).attr('id') === 'btn-add-test-row' ? '#dynamic-tests-container' : '#edit-dynamic-tests-container';
              $(target).append(trTemplate);
          });

          $(document).on('click', '.remove-row', function() {
              $(this).closest('.test-item-row').remove();
          });

          // =============================================
          // DOCTOR SELECT MANAGEMENT (for Reports)
          // =============================================
          function fetchReportDoctors(selectedValue = null) {
              $.get("{{ route('doctors.suggestions') }}", function(data) {
                  let options = '<option value="">-- Select Doctor --</option><option value="Self">Self</option>';
                  data.forEach(function(doctor) {
                      let qual = doctor.qualification ? doctor.qualification : '';
                      options += `<option value="${doctor.name}" data-id="${doctor.id}" data-phone="${doctor.phone || ''}" data-email="${doctor.email || ''}" data-qualification="${qual}">${doctor.name}${qual ? ' (' + qual + ')' : ''}</option>`;
                  });
                  $('.report-doctor-select').html(options);
                  if (selectedValue) {
                      $('.report-doctor-select').val(selectedValue);
                  }
              });
          }

          // Open Add Doctor modal from report
          $(document).on('click', '.btn-add-report-doctor', function() {
              $('#modal-add-report-doctor').modal('show');
          });

          // Open Edit Doctor modal from report
          $(document).on('click', '.btn-edit-report-doctor', function() {
              let select = $(this).siblings('.report-doctor-select');
              let selectedOption = select.find('option:selected');
              let docId = selectedOption.data('id');
              if (!docId) { alert('Please select a valid doctor to edit.'); return; }
              $('#edit-report-doc-id').val(docId);
              $('#edit-report-doc-name').val(selectedOption.val());
              $('#edit-report-doc-qualification').val(selectedOption.data('qualification'));
              $('#edit-report-doc-phone').val(selectedOption.data('phone'));
              $('#edit-report-doc-email').val(selectedOption.data('email'));
              $('#modal-edit-report-doctor').modal('show');
          });

          $('#btn-save-report-doctor').click(function() {
              let formData = $('#form-add-report-doctor').serialize();
              $.post("{{ route('doctors.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-doctor').modal('hide');
                  $('#form-add-report-doctor')[0].reset();
                  fetchReportDoctors(response.doctor.name);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save doctor.'));
              });
          });

          $('#btn-update-report-doctor').click(function() {
              let docId = $('#edit-report-doc-id').val();
              $.ajax({
                  url: "/doctors/" + docId,
                  type: 'PUT',
                  data: $('#form-edit-report-doctor').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-doctor').modal('hide');
                      fetchReportDoctors(response.doctor.name);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update doctor.'));
                  }
              });
          });

          fetchReportDoctors();
          // =============================================

		  // Save Report
		  $('#btn-save-report').click(function() {
              if(!$('#form-add-report')[0].checkValidity()) {
                  $('#form-add-report')[0].reportValidity();
                  return;
              }

			  let btn = $(this);
              btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
			  
			  $.post("{{ route('reports.store') }}", $('#form-add-report').serialize(), function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
                  let errorMsg = xhr.responseJSON.message || "Error saving report. Please check all fields.";
				  alert(errorMsg);
                  btn.html('Generate Report').prop('disabled', false);
			  });
		  });

          // Edit Report Load Data
          $(document).on('click', '.btn-edit', function(e) {
              e.preventDefault();
              let id = $(this).data('id');
              $.get("/reports/" + id, function(data) {
                  $('#edit-report-id').val(data.id);
                  $('#edit-patient-id').val(data.patient_id);
                  $('#edit-report-doctor').val(data.doctor_name);
                  $('#edit-sample-date').val(moment(data.sample_received_on).format('YYYY-MM-DDTHH:mm'));
                  $('#edit-release-date').val(moment(data.report_released_on).format('YYYY-MM-DDTHH:mm'));
                  $('#edit-report-notes').val(data.notes || '');
                  $('#edit-report-signature-id').val(data.report_signature_id || '');
                  $('#edit-signature-pin').val('');
                  updateSignaturePinRequirement($('#edit-report-signature-id')[0]);
                  
                  let container = $('#edit-dynamic-tests-container');
                  container.empty();
                  
                  if(data.results && data.results.length > 0) {
                      data.results.forEach(item => {
                          let catOptions = `<option value="">Category</option>`;
                          @foreach($categories as $cat)
                            catOptions += `<option value="{{ $cat->name }}" ${item.category == '{{ $cat->name }}' ? 'selected' : ''}>{{ $cat->name }}</option>`;
                          @endforeach

                          let subOptions = `<option value="">Sub-Category</option>`;
                          @foreach($subCategories as $sub)
                            subOptions += `<option value="{{ $sub->name }}" ${item.subcategory == '{{ $sub->name }}' ? 'selected' : ''}>{{ $sub->name }}</option>`;
                          @endforeach

                           let testOptions = `<option value="">-- Select Test --</option>`;
                           @foreach($tests as $test)
                             testOptions += `<option value="{{ $test->name }}" 
                                data-unit="{{ $test->parameter->unit ?? '' }}"
                                data-male-ref="{{ $test->parameter->male_reference ?? '' }}"
                                data-female-ref="{{ $test->parameter->female_reference ?? '' }}"
                                data-male-min="{{ $test->parameter->male_min ?? '' }}"
                                data-male-max="{{ $test->parameter->male_max ?? '' }}"
                                data-female-min="{{ $test->parameter->female_min ?? '' }}"
	                                data-female-max="{{ $test->parameter->female_max ?? '' }}"
	                                data-critical-low="{{ $test->parameter->critical_low ?? '' }}"
	                                data-critical-high="{{ $test->parameter->critical_high ?? '' }}"
		                                data-reference-intervals="{{ $test->referenceIntervals->map->only(['gender', 'age_min', 'age_max', 'reference_text', 'min_value', 'max_value'])->values()->toJson() }}"
	                                data-is-immunoassay="{{ $test->parameter->is_immunoassay ?? 0 }}"
                                data-bio-ref="{{ $test->parameter->biological_reference ?? '' }}"
                                data-normal="{{ $test->description }}" ${item.name == '{{ $test->name }}' ? 'selected' : ''}>{{ $test->name }}</option>`;
                           @endforeach

                           let flagOptions = `
                             <option value="" ${!item.flag ? 'selected' : ''}>-</option>
                             <option value="H" ${item.flag == 'H' ? 'selected' : ''}>H</option>
                             <option value="L" ${item.flag == 'L' ? 'selected' : ''}>L</option>
                             <option value="N" ${item.flag == 'N' ? 'selected' : ''}>N</option>
                             <option value="B" ${item.flag == 'B' ? 'selected' : ''}>B</option>
                             <option value="P" ${item.flag == 'P' ? 'selected' : ''}>P</option>
                           `;

                           container.append(`
                             <div class="row test-item-row align-items-center mb-3 pb-3 border-bottom">
                                 <div class="col-md-2 col-12 mb-md-0 mb-2">
                                     <select class="form-select" name="test_category[]">
                                         ${catOptions}
                                     </select>
                                 </div>
                                 <div class="col-md-2 col-12 mb-md-0 mb-2 px-1">
                                     <select class="form-select" name="test_subcategory[]">
                                         ${subOptions}
                                     </select>
                                 </div>
                                 <div class="col-md-2 col-12 mb-md-0 mb-2 px-1">
                                     <select class="form-select test-selector-dynamic" name="test_name[]" required>
                                         ${testOptions}
                                         <option value="Immunoassay Test" ${item.name == 'Immunoassay Test' ? 'selected' : ''}>Immunoassay Test (Auto-calc)</option>
                                     </select>
                                 </div>
                                 <div class="col-md-1 col-6 mb-md-0 mb-2 px-1">
                                     <input type="text" class="form-control observed-value-input" name="observed_value[]" value="${item.observed_value || ''}" required placeholder="Observed" list="list-observed">
                                 </div>
                                 <div class="col-md-1 col-6 mb-md-0 mb-2 px-1">
                                     <select class="form-select" name="test_unit[]">
                                         <option value="">Unit</option>
                                         @foreach($units as $u)
                                             <option value="{{ $u->name }}" ${item.unit == '{{ $u->name }}' ? 'selected' : ''}>{{ $u->name }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                                 <div class="col-md-3 col-12 mb-md-0 mb-2 px-1">
                                     <input type="text" class="form-control normal-val-dynamic" name="normal_value[]" value="${item.normal_value || item.biological_reference || ''}" placeholder="Reference Value">
                                     <input type="hidden" class="bio-val-dynamic" name="biological_reference[]" value="${item.biological_reference || ''}">
                                     <input type="hidden" class="flag-selector" name="test_flag[]" value="${item.flag || ''}">
                                 </div>
                                 <div class="col-md-1 col-2 text-center">
                                     <button type="button" class="btn btn-danger-light btn-sm remove-row" title="Remove Test"><i class="fa fa-trash"></i></button>
                                 </div>
                             </div>
                           `);
                      });
                  } else {
                      container.append(trTemplate);
                  }
              });
          });

          // Update Report
          $('#btn-update-report').click(function() {
              if(!$('#form-edit-report')[0].checkValidity()) {
                  $('#form-edit-report')[0].reportValidity();
                  return;
              }

			  let btn = $(this);
              let id = $('#edit-report-id').val();
              btn.html('<i class="fa fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

			  
			  $.ajax({
                  url: "/reports/" + id,
                  type: 'PUT',
                  data: $('#form-edit-report').serialize(),
                  success: function(response) {
                      alert(response.success);
                      location.reload();
                  },
                  error: function(xhr) {
                      alert(xhr.responseJSON.message || "Error updating report.");
                      btn.html('Update Report').prop('disabled', false);
                  }
              });
		  });

		  // View Report (Chrome-like Viewer Logic)
          let currentZoom = 1;
          const ZOOM_STEP = 0.1;
          const MAX_ZOOM = 2.0;
          const MIN_ZOOM = 0.25;

          // ── Header Toggle ──────────────────────────────────────
          let showReportHeader = true;

          $(document).on('click', '.pdf-header-toggle', function() {
              showReportHeader = $(this).attr('id') === 'btn-with-header';
              $('.pdf-header-toggle').removeClass('is-active');
              $(this).addClass('is-active');

              // Update Dropdown UI
              if (showReportHeader) {
                  $('#current-mode-text').text('With Header');
                  $('#current-mode-icon').removeClass('fa-file-o').addClass('fa-id-card-o');
              } else {
                  $('#current-mode-text').text('No Header');
                  $('#current-mode-icon').removeClass('fa-id-card-o').addClass('fa-file-o');
              }

              let id = $('#btn-viewer-download').data('current-id');
              if (!id) return;
              $('#pdf-canvas-container').html(`<div class="text-center py-100" style="color:#64748b;"><div style="width:40px;height:40px;border:3px solid rgba(99,102,241,0.3);border-top-color:#6366f1;border-radius:50%;animation:spin 0.9s linear infinite;margin:0 auto 16px;"></div><p style="font-size:13px;">Re-generating...</p></div>`);
              $.get("/reports/" + id, function(data) {
                  const doc = createPDFDocument(data, showReportHeader);
                  const pdfBlob = doc.output('blob');
                  renderPDF(URL.createObjectURL(pdfBlob));
              }).fail(function(xhr) {
                  $('#pdf-canvas-container').html(`<div class="alert alert-danger m-20">Could not reload report preview. ${xhr.responseJSON?.message || 'Please try again.'}</div>`);
              });
          });
          // ──────────────────────────────────────────────────────

		  $(document).on('click', '.btn-view', function(e) {
			  e.preventDefault();
			  let id = $(this).data('id');

              // Open modal
              $('#modal-view-report').modal('show');

              // Reset header toggle to 'With Header'
              showReportHeader = true;
              $('.pdf-header-toggle').removeClass('is-active');
              $('#btn-with-header').addClass('is-active');
              
              $('#btn-viewer-download').data('current-id', id);
              $('#btn-viewer-print').data('current-id', id);
              $('#btn-viewer-share').data('current-id', id);
              
			  $('#pdf-canvas-container').html(`
				<div class="text-center py-100">
					<div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
						<span class="visually-hidden">Loading...</span>
					</div>
					<p class="mt-20 text-white-50 fs-16">Generating real PDF preview...</p>
				</div>
			  `);
              
              // Reset zoom
              currentZoom = 1;
              applyZoom();
			  
			  $.get("/reports/" + id, function(data) {
                  let p = data.patient;
                  let filename = `Report_${p.first_name}_${id}.pdf`;
                  $('#viewer-filename-link').text(filename);
                  $('#viewer-filename-link').attr('title', `Click to open ${filename} directly`);

                  // Generate PDF Blob
                  const doc = createPDFDocument(data, showReportHeader);
                  const pdfBlob = doc.output('blob');
                  const pdfUrl = URL.createObjectURL(pdfBlob);

                  // Render PDF using PDF.js
                  renderPDF(pdfUrl).then(() => {
                      // On mobile, default to fit width
                      if (window.innerWidth < 768) {
                          $('#fit-width').click();
                      }
                  });
			  }).fail(function(xhr) {
                  $('#pdf-canvas-container').html(`<div class="alert alert-danger m-20">Could not load report preview. ${xhr.responseJSON?.message || 'Please try again.'}</div>`);
              });
		  });

          async function renderPDF(url) {
              try {
                  if (!window.pdfjsLib) {
                      throw new Error('PDF preview engine did not load.');
                  }

                  const loadingTask = pdfjsLib.getDocument(url);
                  const pdf = await loadingTask.promise;
                  const container = document.getElementById('pdf-canvas-container');
                  container.innerHTML = ''; // Clear loader

                  for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                      const page = await pdf.getPage(pageNum);
                      const viewport = page.getViewport({ scale: 1.5 }); // High resolution render
                      
                      const wrapper = document.createElement('div');
                      wrapper.className = 'pdf-canvas-wrapper mb-20';
                      
                      const canvas = document.createElement('canvas');
                      const context = canvas.getContext('2d');
                      canvas.height = viewport.height;
                      canvas.width = viewport.width;
                      canvas.dataset.baseWidth = viewport.width;
                      canvas.style.width = `${viewport.width * currentZoom}px`;

                      const renderContext = {
                          canvasContext: context,
                          viewport: viewport
                      };
                      
                      wrapper.appendChild(canvas);
                      container.appendChild(wrapper);
                      
                      await page.render(renderContext).promise;
                  }

                  applyZoom();
              } catch (error) {
                  console.error('PDF Rendering Error:', error);
                  $('#pdf-canvas-container').html(`<div class="alert alert-danger m-20">Error rendering PDF: ${error.message}</div>`);
              }
          }

          // Zoom Functions
          function applyZoom() {
              $('#pdf-page-container').css('transform', 'none');
              $('#pdf-canvas-container canvas').each(function() {
                  const baseWidth = parseFloat(this.dataset.baseWidth || this.width || 794);
                  $(this).css('width', `${baseWidth * currentZoom}px`);
              });
              $('#zoom-text').text(`${Math.round(currentZoom * 100)}%`);
          }

          $('#zoom-in').click(function() {
              if (currentZoom < MAX_ZOOM) {
                  currentZoom += ZOOM_STEP;
                  applyZoom();
              }
          });

          $('#zoom-out').click(function() {
              if (currentZoom > MIN_ZOOM) {
                  currentZoom -= ZOOM_STEP;
                  applyZoom();
              }
          });

          $('#fit-width').click(function() {
              let containerWidth = $('#pdf-viewport').width() - (window.innerWidth < 768 ? 14 : 80);
              let firstCanvas = $('#pdf-canvas-container canvas').first()[0];
              let pageWidth = parseFloat(firstCanvas?.dataset.baseWidth || firstCanvas?.width || 794);
              currentZoom = containerWidth / pageWidth;
              if (currentZoom > MAX_ZOOM) currentZoom = MAX_ZOOM;
              if (currentZoom < MIN_ZOOM) currentZoom = MIN_ZOOM;
              applyZoom();
          });

          $('#fit-page').click(function() {
              let containerHeight = $('#pdf-viewport').height() - 80;
              let pageHeight = 1123; // A4 Height in px roughly
              currentZoom = containerHeight / pageHeight;
              if (currentZoom > MAX_ZOOM) currentZoom = MAX_ZOOM;
              if (currentZoom < MIN_ZOOM) currentZoom = MIN_ZOOM;
              applyZoom();
          });

          // Keyboard Navigation
          $(document).on('keydown', function(e) {
              // Only active when PDF viewer is open
              if (!$('#modal-view-report').hasClass('show')) return;
              
              const viewport = $('#pdf-viewport')[0];
              const scrollStep = 100;
              const pageStep = $('#pdf-viewport').height() * 0.85;
              
              switch(e.key) {
                  case 'ArrowDown':
                      viewport.scrollTop += scrollStep;
                      e.preventDefault();
                      break;
                  case 'ArrowUp':
                      viewport.scrollTop -= scrollStep;
                      e.preventDefault();
                      break;
                  case 'PageDown':
                  case ' ': // Space bar
                      if (!$(e.target).is('input, textarea, select')) {
                          $('#pdf-viewport').stop().animate({ scrollTop: $('#pdf-viewport').scrollTop() + pageStep }, 200);
                          e.preventDefault();
                      }
                      break;
                  case 'PageUp':
                      $('#pdf-viewport').stop().animate({ scrollTop: $('#pdf-viewport').scrollTop() - pageStep }, 200);
                      e.preventDefault();
                      break;
                  case 'Home':
                      $('#pdf-viewport').stop().animate({ scrollTop: 0 }, 300);
                      e.preventDefault();
                      break;
                  case 'End':
                      $('#pdf-viewport').stop().animate({ scrollTop: viewport.scrollHeight }, 300);
                      e.preventDefault();
                      break;
                  case '+':
                  case '=':
                      if (e.ctrlKey) { $('#zoom-in').click(); e.preventDefault(); }
                      break;
                  case '-':
                  case '_':
                      if (e.ctrlKey) { $('#zoom-out').click(); e.preventDefault(); }
                      break;
                  case '0':
                      if (e.ctrlKey) { currentZoom = 1; applyZoom(); e.preventDefault(); }
                      break;
              }
          });

          $('#btn-viewer-fullscreen').click(function() {
              const elem = document.querySelector('.pdf-viewer-wrapper');
              if (!document.fullscreenElement) {
                  elem.requestFullscreen().catch(err => {
                      alert(`Error attempting to enable full-screen mode: ${err.message}`);
                  });
                  $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
              } else {
                  document.exitFullscreen();
                  $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
              }
          });

          $('#btn-viewer-print').click(function() {
              // Temporarily change title to remove "Awwal Lab - Dashboard" from print headers
              const oldTitle = document.title;
              document.title = $('#viewer-filename-link').text().replace('.pdf', '');
              window.print();
              // Restore title after print dialog closes
              setTimeout(() => { document.title = oldTitle; }, 500);
          });

          $(document).on('click', '#viewer-filename-link', function() {
              let id = $('#btn-viewer-download').data('current-id');
              generateAndOpenPDF(id);
          });

          function generateAndOpenPDF(reportId) {
              const { jsPDF } = window.jspdf;
              $.get("/reports/" + reportId, function(data) {
                  const doc = createPDFDocument(data, showReportHeader);
                  window.open(doc.output('bloburl'), '_blank');
              });
          }

          // Helper to create the Awwal-style lab report PDF.
          function createPDFDocument(data, showHeader = true) {
              const { jsPDF } = window.jspdf;
              const doc = new jsPDF();
              const p = data.patient;
              const referenceNo = (p.patient_id || '').replace('#P-', '').replace('#', '');
              const patientName = `${(p.first_name || '').toUpperCase()} ${(p.last_name || '').toUpperCase()}`.trim();
              const sex = (p.gender || '').toUpperCase();
              const reportDate = moment(data.sample_received_on).format('DD-MMM-YYYY - hh:mm:ss A');
              const printedDate = moment().format('DD-MMM-YYYY - hh:mm:ss A');
              const reportNotes = (data.notes || '').trim();
              const signature = data.signature || null;

              const pageW = doc.internal.pageSize.getWidth();
              const pageH = doc.internal.pageSize.getHeight();
              const left = 21;
              const tableW = 173;
	              const col1 = 61;
	              const col2 = 44;
	              const col4 = 14;
	              const col3 = tableW - col1 - col2 - col4;
              const footerTop = 269;
              let pageNo = 1;

              function addShell(isFirstPage = true) {
                  if (showHeader) {
                      doc.addImage(REPORT_HEADER_IMAGE, 'PNG', 0, 0, pageW, 26.5);
                  }

                  doc.addImage(REPORT_FOOTER_IMAGE, 'PNG', 0, footerTop, pageW, pageH - footerTop);
                  doc.setTextColor(0);
                  doc.setDrawColor(0);
                  doc.setLineWidth(0.25);

                  const infoY = isFirstPage ? 44 : 44;
                  doc.setFont('times', 'normal');
                  doc.setFontSize(11);

                  doc.text(isFirstPage ? 'Patient Name' : 'Name', left + 2, infoY);
                  doc.text(':', left + 28, infoY);
                  doc.setFont('times', 'bold');
                  doc.text(patientName, left + 31, infoY);

                  doc.setFont('times', 'normal');
                  doc.text('Age', 132, infoY);
                  doc.text(':', 158, infoY);
                  doc.text(`${p.age || ''}`, 160, infoY);
                  doc.text(`Sex : ${sex}`, 171, infoY);

                  doc.text('Specimen', 132, infoY + 5);
                  doc.text(':', 158, infoY + 5);

                  doc.text('Reference No', left + 2, infoY + 10);
                  doc.text(':', left + 28, infoY + 10);
                  doc.text(referenceNo, left + 31, infoY + 10);

                  doc.text('Date', 132, infoY + 10);
                  doc.text(':', 158, infoY + 10);
                  doc.text(isFirstPage ? reportDate : moment(data.sample_received_on).format('DD-MMM-YYYY'), 160, infoY + 10);

                  doc.text('Referred By', left + 2, infoY + 15);
                  doc.text(':', left + 28, infoY + 15);
                  doc.setFont('times', 'bold');
                  doc.text(data.doctor_name || '', left + 31, infoY + 15);
                  doc.setFont('times', 'normal');

                  if (isFirstPage) {
                      doc.text('Printed Date', 132, infoY + 15);
                      doc.text(':', 158, infoY + 15);
                      doc.text(printedDate, 160, infoY + 15);
                  }

                  doc.line(0, infoY + 18, pageW, infoY + 18);
              }

              function addNewPage() {
                  doc.addPage();
                  pageNo += 1;
                  addShell(false);
                  return 70;
              }

              function drawCategoryTitle(title, y) {
                  doc.setFont('times', 'bold');
                  doc.setFontSize(13);
                  doc.text((title || 'GENERAL').toUpperCase(), pageW / 2, y, { align: 'center' });
                  return y + 9;
              }

              function drawTableHeader(y) {
                  doc.setFont('times', 'bold');
                  doc.setFontSize(11);
	                  doc.rect(left, y, tableW, 8);
	                  doc.line(left + col1, y, left + col1, y + 8);
	                  doc.line(left + col1 + col2, y, left + col1 + col2, y + 8);
	                  doc.line(left + col1 + col2 + col3, y, left + col1 + col2 + col3, y + 8);
	                  doc.text('Parameter', left + 5, y + 5.5);
	                  doc.text('Observed Value', left + col1 + col2 / 2, y + 5.5, { align: 'center' });
	                  doc.text('Reference Value', left + col1 + col2 + col3 / 2, y + 5.5, { align: 'center' });
	                  doc.text('Flag', left + col1 + col2 + col3 + col4 / 2, y + 5.5, { align: 'center' });
	                  return y + 8;
	              }

	              function drawCellRow(y, name, observed, reference, flag = '', boldFirst = false) {
	                  doc.setFontSize(11);
	                  const nameLines = doc.splitTextToSize(name || '', col1 - 4);
	                  const observedLines = doc.splitTextToSize(observed || '', col2 - 4);
	                  const refLines = doc.splitTextToSize(reference || '', col3 - 4);
                  const lineCount = Math.max(nameLines.length, observedLines.length, refLines.length, 1);
                  const rowH = Math.max(6, lineCount * 5.2);

                  if (y + rowH > footerTop - 12) {
                      y = addNewPage();
                      y = drawTableHeader(y);
                  }

	                  doc.rect(left, y, tableW, rowH);
	                  doc.line(left + col1, y, left + col1, y + rowH);
	                  doc.line(left + col1 + col2, y, left + col1 + col2, y + rowH);
	                  doc.line(left + col1 + col2 + col3, y, left + col1 + col2 + col3, y + rowH);

                  doc.setFont('times', boldFirst ? 'bold' : 'normal');
                  doc.text(nameLines, left + 2, y + 4.5);
                  doc.setFont('times', 'bold');
                  doc.text(observedLines, left + col1 + 2, y + 4.5);
	                  doc.setFont('times', 'normal');
	                  doc.text(refLines, left + col1 + col2 + 2, y + 4.5);
	                  doc.setFont('times', 'bold');
	                  if (flag === 'C') doc.setTextColor(208, 0, 0);
	                  doc.text(flag || '', left + col1 + col2 + col3 + col4 / 2, y + 4.5, { align: 'center' });
	                  doc.setTextColor(0);
	                  return y + rowH;
	              }

              function imageFormatFromDataUrl(dataUrl) {
                  if (!dataUrl || dataUrl.indexOf('image/jpeg') !== -1 || dataUrl.indexOf('image/jpg') !== -1) {
                      return 'JPEG';
                  }

                  return 'PNG';
              }

              addShell(true);

              let groupedResults = {};
              (data.results || []).forEach(r => {
                  const cat = (r.category || 'GENERAL').toUpperCase();
                  if (!groupedResults[cat]) groupedResults[cat] = [];
                  groupedResults[cat].push(r);
              });

              let y = 72;
              Object.keys(groupedResults).forEach(cat => {
                  if (y > footerTop - 35) y = addNewPage();
                  y = drawCategoryTitle(cat, y);
                  y = drawTableHeader(y);

                  let lastSubheading = null;
                  groupedResults[cat].forEach(r => {
	                      const subheading = (r.subcategory || '').trim();
	                      if (subheading && subheading !== lastSubheading) {
	                          y = drawCellRow(y, subheading.toUpperCase(), '', '', '', true);
	                          lastSubheading = subheading;
	                      }

	                      const observed = `${r.observed_value || ''}${r.unit ? '  ' + r.unit : ''}`.trim();
	                      const reference = r.normal_value || r.biological_reference || '';
	                      y = drawCellRow(y, r.name || '', observed, reference, r.flag || '');
	                  });

                  y += 8;
              });

              if (y > footerTop - 55) y = addNewPage();
              doc.setFont('times', 'normal');
              doc.setFontSize(11);
              doc.text('Note :', left, y + 5);

              if (reportNotes) {
                  const noteLines = doc.splitTextToSize(reportNotes, 116);
                  if (y + 8 + noteLines.length * 5 > footerTop - 18) {
                      y = addNewPage();
                      doc.text('Note :', left, y + 5);
                  }
                  doc.text(noteLines, left + 13, y + 5);
              }

              if (signature && signature.image_data) {
                  try {
                      doc.addImage(signature.image_data, imageFormatFromDataUrl(signature.image_data), 154, footerTop - 34, 38, 18, undefined, 'FAST');
                  } catch (error) {
                      console.warn('Could not add signature image:', error);
                  }
              }

              doc.setFont('times', 'bold');
              doc.text(signature?.name || 'Medi Technician', 174, footerTop - 8, { align: 'center' });
              doc.setFont('times', 'normal');
              if (pageNo > 1) {
                  doc.setFontSize(9);
                  doc.text(`Page No :${pageNo}`, 12, 36);
              }

              return doc;
          }

          $('#btn-viewer-download').click(function() {
              let id = $(this).data('current-id');
              generateAndDownloadPDF(id, showReportHeader);
          });

          // Share Report PDF
          $('#btn-viewer-share').click(function() {
              let id = $(this).data('current-id');
              let btn = $(this);
              let originalHtml = btn.html();
              btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

              $.get("/reports/" + id, function(data) {
                  const doc = createPDFDocument(data, showReportHeader);
                  const p = data.patient;
                  const filename = `Report_${p.first_name}_${p.last_name}_${id}.pdf`;

                  // Generate PDF as Blob
                  const pdfBlob = doc.output('blob');
                  const pdfFile = new File([pdfBlob], filename, { type: 'application/pdf' });

                  // Try Web Share API (supports file sharing on mobile)
                  if (navigator.share && navigator.canShare && navigator.canShare({ files: [pdfFile] })) {
                      navigator.share({
                          title: `Lab Report - ${p.first_name} ${p.last_name}`,
                          text: `Lab Report for ${p.first_name} ${p.last_name}. Please find the attached PDF report.`,
                          files: [pdfFile]
                      }).then(() => {
                          console.log('Report shared successfully.');
                      }).catch(err => {
                          if (err.name !== 'AbortError') {
                              console.warn('Share failed, falling back:', err);
                              shareViaWhatsApp(p, filename, pdfBlob);
                          }
                      });
                  } else if (navigator.share) {
                      // Share API available but no file support — share link/text
                      navigator.share({
                          title: `Lab Report - ${p.first_name} ${p.last_name}`,
                          text: `Lab Report for ${p.first_name} ${p.last_name} (ID: ${p.patient_id}). Generated from Awwal Lab Management System.`,
                      }).catch(err => console.warn('Share failed:', err));
                  } else {
                      // Desktop fallback: WhatsApp + auto-download
                      shareViaWhatsApp(p, filename, pdfBlob);
                  }

                  btn.html(originalHtml).prop('disabled', false);
              }).fail(function() {
                  alert('Failed to generate report for sharing.');
                  btn.html(originalHtml).prop('disabled', false);
              });
          });

          function shareViaWhatsApp(patient, filename, pdfBlob) {
              // Auto-download the PDF first
              const url = URL.createObjectURL(pdfBlob);
              const a = document.createElement('a');
              a.href = url;
              a.download = filename;
              a.click();
              URL.revokeObjectURL(url);

              // Open WhatsApp with a pre-filled message
              const phone = patient.phone ? patient.phone.replace(/\D/g, '') : '';
              const msg = encodeURIComponent(`Lab Report for ${patient.first_name} ${patient.last_name} (ID: ${patient.patient_id}).\n\nPlease find your report PDF that has been downloaded. You can attach it manually.`);
              const waUrl = phone
                  ? `https://wa.me/${phone}?text=${msg}`
                  : `https://wa.me/?text=${msg}`;

              setTimeout(() => window.open(waUrl, '_blank'), 500);
          }

          function generateAndDownloadPDF(reportId, withHeader = true, callback) {
              $.get("/reports/" + reportId, function(data) {
                  const doc = createPDFDocument(data, withHeader);
                  const p = data.patient;
                  doc.save(`Report_${p.first_name}_${reportId}.pdf`);
                  if (callback) callback();
              });
          }

		  // Delete Report
		  $(document).on('click', '.btn-delete', function(e) {
			  e.preventDefault();
              let id = $(this).data('id');
              if(confirm('Are you sure you want to delete this report?')) {
                  $.ajax({
                      url: "/reports/" + id,
                      type: 'DELETE',
                      success: function(response) {
                          alert(response.success);
                          location.reload();
                      }
                  });
              }
		  });

	  });
  </script>

  <!-- Add Report Doctor Modal -->
  <div class="modal center-modal fade" id="modal-add-report-doctor" tabindex="-1" style="z-index: 1070;">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Add New Doctor</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-report-doctor">
				@csrf
				<div class="form-group">
					<label class="form-label">Doctor Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="name" required placeholder="e.g. Dr. John Doe">
				</div>
				<div class="form-group">
					<label class="form-label">Qualification</label>
					<input type="text" class="form-control" name="qualification" placeholder="e.g. MBBS, MD">
				</div>
				<div class="form-group">
					<label class="form-label">Phone No</label>
					<input type="text" class="form-control" name="phone" placeholder="Phone Number">
				</div>
				<div class="form-group">
					<label class="form-label">Email</label>
					<input type="email" class="form-control" name="email" placeholder="Email Address" autocomplete="new-password">
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-save-report-doctor">Save Doctor</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Report Doctor Modal -->
  <div class="modal center-modal fade" id="modal-edit-report-doctor" tabindex="-1" style="z-index: 1070;">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Doctor</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-report-doctor">
				@csrf
				<input type="hidden" name="doctor_id" id="edit-report-doc-id">
				<div class="form-group">
					<label class="form-label">Doctor Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="name" id="edit-report-doc-name" required>
				</div>
				<div class="form-group">
					<label class="form-label">Qualification</label>
					<input type="text" class="form-control" name="qualification" id="edit-report-doc-qualification">
				</div>
				<div class="form-group">
					<label class="form-label">Phone No</label>
					<input type="text" class="form-control" name="phone" id="edit-report-doc-phone">
				</div>
				<div class="form-group">
					<label class="form-label">Email</label>
					<input type="email" class="form-control" name="email" id="edit-report-doc-email" autocomplete="new-password">
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-update-report-doctor">Update Doctor</button>
		  </div>
		</div>
	  </div>
  </div>

@endsection
