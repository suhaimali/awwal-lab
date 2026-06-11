@extends('layouts.app')
@section('title', ' | Patients')
@section('page-title', 'Patients')

@push('styles')
<style>
    /* Patient Table specific styling */
    .patients-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    /* Avatar style */
    .patient-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.5px;
        flex-shrink: 0;
        box-shadow: inset 0 -2px 0 rgba(0, 0, 0, 0.05);
    }

    .patient-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 14.5px;
        line-height: 1.2;
    }

    .patient-meta {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .meta-separator {
        color: #cbd5e1;
    }

    /* Contact details styling */
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #475569;
    }

    .contact-item i {
        font-size: 12px;
        color: #94a3b8;
        width: 18px;
    }

    .contact-item.email {
        color: #64748b;
        font-size: 12px;
    }

    /* Financial block */
    .financial-block {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .net-balance {
        font-weight: 700;
        font-size: 14.5px;
    }

    .net-balance.paid {
        color: #059669;
    }

    .net-balance.due {
        color: #ea580c;
    }

    .financial-details {
        font-size: 11px;
        color: #64748b;
        display: flex;
        gap: 8px;
        white-space: nowrap;
    }

    .financial-details span {
        display: flex;
        align-items: center;
    }

    /* Custom badge for ID */
    .patient-id-badge {
        font-family: monospace;
        font-weight: 600;
        font-size: 12px;
        background: #f1f5f9;
        color: #475569;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    /* Payment mode badge */
    .payment-mode-badge {
        font-weight: 600;
        font-size: 11.5px;
        background: #eff6ff;
        color: #1a56db;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid #bfdbfe;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Action buttons styling */
    .action-buttons {
        display: inline-flex;
        gap: 6px;
    }

    .btn-action-circle {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 13px;
        text-decoration: none;
    }

    .btn-action-circle:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
        transform: translateY(-1px);
    }

    .btn-action-circle.btn-invoice:hover {
        border-color: #059669;
        color: #059669;
        background: #d1fae5;
    }

    .btn-action-circle.btn-delete-action:hover {
        border-color: #dc2626;
        color: #dc2626;
        background: #fee2e2;
    }

    /* Status badge */
    .status-dot-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-dot-badge.active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-dot-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-dot.active {
        background: #059669;
        box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2);
        animation: pulse-green 2s infinite;
    }

    .status-dot.inactive {
        background: #dc2626;
    }

    @keyframes pulse-green {
        0% {
            box-shadow: 0 0 0 0 rgba(5, 150, 105, 0.4);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(5, 150, 105, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(5, 150, 105, 0);
        }
    }

    /* Premium Form Inputs inside Modals */
    .modal-aw .form-label {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .modal-aw .form-control, 
    .modal-aw .form-select {
        border: 1.5px solid #cbd5e1;
        border-radius: 9px;
        padding: 9px 12px;
        font-size: 13.5px;
        color: #1e293b;
        background-color: #ffffff;
        outline: none;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .modal-aw .form-control:focus, 
    .modal-aw .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1) !important;
        background-color: #ffffff;
    }

    .modal-aw .form-control[readonly] {
        background-color: #f8fafc;
        color: #64748b;
        border-color: #e2e8f0;
    }

    /* Reference Doctor Input Group Custom style */
    .modal-aw .input-group .form-select {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .modal-aw .input-group .btn {
        border-radius: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        font-size: 13px;
        border: 1.5px solid #cbd5e1;
        border-left: none;
        background: #ffffff;
        color: #64748b;
        transition: all 0.2s ease;
    }

    .modal-aw .input-group .btn:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .modal-aw .input-group .btn-success {
        color: #059669;
    }
    .modal-aw .input-group .btn-success:hover {
        background: #d1fae5;
        color: #059669;
        border-color: #a7f3d0;
    }

    .modal-aw .input-group .btn-warning {
        color: #d97706;
    }
    .modal-aw .input-group .btn-warning:hover {
        background: #fef3c7;
        color: #d97706;
        border-color: #fde68a;
    }

    .modal-aw .input-group .btn-danger {
        color: #dc2626;
        border-top-right-radius: 9px;
        border-bottom-right-radius: 9px;
    }
    .modal-aw .input-group .btn-danger:hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }

    /* Modal layout styling */
    .modal-aw .form-group {
        margin-bottom: 16px;
    }

    /* Test Row inside modals */
    .modal-aw .test-row {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px !important;
        align-items: center;
        transition: all 0.15s ease;
    }

    .modal-aw .test-row:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }

    .fs-11 {
        font-size: 11px !important;
    }

    /* === View Patient Modal === */
    #modal-view-patient .modal-content { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.12); }
    #modal-view-patient .vp-header { background: linear-gradient(135deg, #1a56db 0%, #3b82f6 100%); padding: 28px 24px 48px; position: relative; text-align: center; color: #fff; }
    #modal-view-patient .vp-avatar { width: 72px; height: 72px; border-radius: 50%; background: rgba(255,255,255,0.25); border: 3px solid rgba(255,255,255,0.6); margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 800; color: #fff; letter-spacing: 1px; backdrop-filter: blur(4px); }
    #modal-view-patient .vp-name { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
    #modal-view-patient .vp-meta { display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; opacity: 0.9; flex-wrap: wrap; }
    #modal-view-patient .vp-meta .vp-id-badge { background: rgba(255,255,255,0.2); border-radius: 20px; padding: 2px 10px; font-weight: 600; font-size: 11px; letter-spacing: 0.5px; }
    #modal-view-patient .vp-body { background: #fff; padding: 0 24px 24px; margin-top: -24px; border-radius: 20px 20px 0 0; position: relative; z-index: 1; }
    #modal-view-patient .vp-status-row { display: flex; justify-content: center; margin: -12px 0 20px; }
    #modal-view-patient .vp-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 480px) { #modal-view-patient .vp-grid { grid-template-columns: 1fr; } }
    #modal-view-patient .vp-grid .vp-full { grid-column: 1 / -1; }
    #modal-view-patient .vp-field { background: #f8fafc; border-radius: 10px; padding: 12px 14px; border: 1px solid #f1f5f9; }
    #modal-view-patient .vp-field-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; margin-bottom: 4px; display: flex; align-items: center; gap: 5px; }
    #modal-view-patient .vp-field-label i { font-size: 9px; color: #1a56db; }
    #modal-view-patient .vp-field-value { font-size: 14px; font-weight: 600; color: #1e293b; word-break: break-word; }
    #modal-view-patient .modal-footer { background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 14px 20px; border-radius: 0 0 16px 16px; }

</style>
@endpush

@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-users"></i></div>
        <div>
            <div>Patients</div>
            <div style="font-size:13px;font-weight:400;color:var(--text-muted);margin-top:2px;">Manage all patient records</div>
        </div>
    </div>
    <button type="button" class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-patient">
        <i class="fa fa-plus"></i> <span>Add New Patient</span>
    </button>
</div>
<section>
<div class="aw-card patients-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-users" style="color:var(--primary);"></i> Patient Records</div>
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ $patients->count() }} total patients</span>
            <div style="position:relative;">
                <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                <input type="text" id="patient-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search patients..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="name_1034">
            </div>
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">
            
                <table class="table-modern" id="patient-table">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Patient Details</th>
                            <th>Contact</th>
                            <th>Financial Status</th>
                            <th>Payment Mode</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            @php
                                $latestApp = $patient->appointments->last();
                                $totalPrice = $latestApp->test_price ?? 0;
                                $totalDiscount = $latestApp->discount ?? 0;
                                $netBalance = $latestApp->balance ?? ($totalPrice - $totalDiscount);

                                // Generate initials for avatar
                                $firstNameInitial = substr(trim($patient->first_name), 0, 1);
                                $lastNameInitial = substr(trim($patient->last_name), 0, 1);
                                $initials = strtoupper($firstNameInitial . $lastNameInitial);
                                if (empty($initials)) {
                                    $initials = 'P';
                                }

                                // Set a dynamic background color based on name characters
                                $bgColors = ['#e0e7ff', '#dbeafe', '#e0f2fe', '#d1fae5', '#fef3c7', '#fee2e2', '#f3e8ff'];
                                $textColors = ['#4f46e5', '#2563eb', '#0284c7', '#059669', '#d97706', '#dc2626', '#9333ea'];
                                $colorIdx = (ord($firstNameInitial) + ord($lastNameInitial)) % count($bgColors);
                                $avatarBg = $bgColors[$colorIdx];
                                $avatarText = $textColors[$colorIdx];
                            @endphp
                            <tr>
                                <!-- ID Column -->
                                <td data-label="SL No">
                                    <span class="patient-id-badge">{{ str_replace(['#P-', '#'], '', $patient->patient_id) }}</span>
                                </td>
                                
                                <!-- Patient Details (Avatar, Name, Gender, Age) -->
                                <td data-label="Patient Details">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="patient-avatar" style="background-color: {{ $avatarBg }}; color: {{ $avatarText }};">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="patient-name">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                            <div class="patient-meta d-flex align-items-center gap-2">
                                                <span class="meta-item">{{ $patient->gender }}</span>
                                                <span class="meta-separator">•</span>
                                                <span class="meta-item">{{ $patient->age }} {{ $patient->age_type ?: 'Yrs' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Contact Details (Phone, Email) -->
                                <td data-label="Contact">
                                    <div class="contact-info">
                                        <div class="contact-item">
                                            <i class="fa-solid fa-phone"></i>
                                            <span>{{ $patient->phone }}</span>
                                        </div>
                                        @if($patient->email)
                                            <div class="contact-item email">
                                                <i class="fa-solid fa-envelope"></i>
                                                <span>{{ $patient->email }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Financial Status (Amount, Discount, Balance) -->
                                <td data-label="Financial Status">
                                    <div class="financial-block">
                                        @if($netBalance == 0)
                                            <div class="net-balance paid">
                                                <i class="fa-solid fa-circle-check me-1"></i>₹0.00
                                            </div>
                                        @else
                                            <div class="net-balance due">
                                                ₹{{ number_format($netBalance, 2) }}
                                            </div>
                                        @endif
                                        <div class="financial-details">
                                            <span>Total: ₹{{ number_format($totalPrice, 2) }}</span>
                                            @if($totalDiscount > 0)
                                                <span style="color:#dc2626;">• Disc: ₹{{ number_format($totalDiscount, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Payment Mode -->
                                <td data-label="Payment Mode">
                                    @if($patient->payment_method)
                                        <span class="payment-mode-badge">
                                            <i class="fa-solid fa-wallet"></i>{{ $patient->payment_method }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-muted); font-size:12px; font-style:italic;">—</span>
                                    @endif
                                </td>

                                <!-- Status (Active / Inactive) -->
                                <td data-label="Status">
                                    <span class="status-dot-badge {{ strtolower($patient->status) == 'active' ? 'active' : 'inactive' }}">
                                        <span class="status-dot {{ strtolower($patient->status) == 'active' ? 'active' : 'inactive' }}"></span>
                                        {{ $patient->status }}
                                    </span>
                                </td>
                                
                                <!-- Action Buttons -->
                                <td class="text-end" data-label="Actions">												
                                    <div class="action-buttons justify-content-end">
                                        <button class="btn-action-circle btn-view" data-id="{{ $patient->id }}" data-bs-toggle="modal" data-bs-target="#modal-view-patient" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button class="btn-action-circle btn-invoice" data-id="{{ $patient->id }}" title="PDF Invoice">
                                            <i class="fa fa-file-pdf"></i>
                                        </button>
                                        <button class="btn-action-circle btn-edit" data-id="{{ $patient->id }}" data-bs-toggle="modal" data-bs-target="#modal-edit-patient" title="Edit Record">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button class="btn-action-circle btn-delete-action btn-delete" data-id="{{ $patient->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete-patient" title="Delete Record">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</section>
<!-- Add Patient Modal -->
<div class="modal fade modal-aw" id="modal-add-patient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user-plus me-2"></i>Add New Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
		  <div class="modal-body">
			<form id="form-add-patient">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1035" class="form-label">Patient ID</label>
							<input type="text" class="form-control" name="patient_id" placeholder="Auto-generated if blank" autocomplete="new-password" id="field_1035">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1036" class="form-label">Phone No</label>
							<input type="text" class="form-control" name="phone" placeholder="Phone Number" autocomplete="off" id="field_1036">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1037" class="form-label">First Name</label>
							<input type="text" class="form-control" name="first_name" placeholder="First Name" autocomplete="off" id="field_1037">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1038" class="form-label">Last Name</label>
							<input type="text" class="form-control" name="last_name" placeholder="Last Name" autocomplete="off" id="field_1038">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1039" class="form-label">Gender</label>
							<select class="form-select" name="gender" autocomplete="off" id="field_1039">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
								<option value="Other">Other</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1040" class="form-label">Age</label>
							<div class="input-group flex-nowrap">
								<input type="number" class="form-control" name="age" placeholder="Age" autocomplete="off" id="field_1040" min="0" max="150">
								<select class="form-select" name="age_type" autocomplete="off" style="max-width: 110px;" id="field_1000">
									<option value="Years">Years</option>
									<option value="Months">Months</option>
									<option value="Days">Days</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1041" class="form-label">Email</label>
							<input type="email" class="form-control" name="email" placeholder="Email (Optional)" autocomplete="new-password" id="field_1041">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1042" class="form-label">Status</label>
							<select class="form-select" name="status" autocomplete="off" id="field_1042">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1043" class="form-label">Reference Dr.</label>
							<div class="reference-dr-container">
								<input type="hidden" class="reference-dr-value" name="reference_dr" value="" id="field_1043">
								<div class="input-group flex-nowrap reference-dr-input-group">
									<select class="form-select reference-dr-select" autocomplete="off" id="field_1044" name="name_1045">
										<option value="">-- Select Doctor --</option>
									</select>
									<button type="button" class="btn btn-success btn-add-doctor" title="Add New"><i class="fa fa-plus"></i></button>
									<button type="button" class="btn btn-warning btn-edit-doctor" title="Edit Selected"><i class="fa fa-edit"></i></button>
									<button type="button" class="btn btn-danger btn-delete-doctor" title="Delete Selected"><i class="fa fa-trash"></i></button>
								</div>
								<div class="reference-dr-custom-wrap" style="display:none;">
									<div class="input-group">
										<input type="text" class="form-control reference-dr-custom-input" placeholder="Enter custom doctor name" autocomplete="off" id="field_1046" name="name_1047">
										<button type="button" class="btn btn-outline-secondary btn-back-to-dr-select" title="Back to dropdown" style="font-size:12px;"><i class="fa fa-list"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_payment_method" class="form-label">Payment Mode</label>
							<select class="form-select" name="payment_method" autocomplete="off" id="field_payment_method">
								<option value="">-- Select Payment Mode --</option>
								<option value="Cash">Cash</option>
								<option value="Card">Card</option>
								<option value="UPI">UPI</option>
								<option value="Net Banking">Net Banking</option>
							</select>
						</div>
					</div>
				</div>
				
				<h5 class="mt-4 mb-2 text-primary fw-bold"><i class="fa fa-flask me-2"></i>Select Tests & Financial Details</h5>
				
                <div class="d-none d-md-flex row fw-bold text-muted mb-2 px-3" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <div class="col-md-5">Test Name</div>
                    <div class="col-md-3">Amount (₹)</div>
                    <div class="col-md-3">Discount (₹)</div>
                    <div class="col-md-1 text-center">Action</div>
                </div>

				<div id="add-patient-tests-container" class="mb-3">
					<div class="row test-row mb-2 align-items-center">
						<div class="col-md-5 col-12">
                            <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Test Name</div>
                            <input type="hidden" class="test-name-value" name="test_name[]" value="" id="field_1048">
							<div class="input-group flex-nowrap">
								<select class="form-select add-patient-test-name test-name-select" autocomplete="off" id="field_1049" name="name_1050">
									<option value="">-- Select Test --</option>
									@foreach($labTests as $test)
										<option value="{{ $test->name }}" data-id="{{ $test->id }}" data-price="{{ $test->price }}" data-payment_method="{{ $test->payment_method }}">{{ $test->name }}</option>
									@endforeach
									<option value="__custom__">&#xf040; Custom (type below)</option>
								</select>
								<button type="button" class="btn btn-success btn-add-test" style="background-color: #d1fae5; color: #059669; border-color: #cbd5e1;" title="Add New Test"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-primary btn-edit-test" style="background-color: #dbeafe; color: #2563eb; border-color: #cbd5e1;" title="Edit Selected Test"><i class="fa fa-edit"></i></button>
							</div>
                            <div class="test-name-custom-wrap" style="display:none;">
                                <div class="input-group">
                                    <input type="text" class="form-control test-name-custom-input" placeholder="Enter custom test name" autocomplete="off" id="field_1051" name="name_1052">
                                    <button type="button" class="btn btn-outline-secondary btn-back-to-select" title="Back to dropdown" style="font-size:12px;"><i class="fa fa-list"></i></button>
                                </div>
                            </div>
						</div>
						<div class="col-md-3 col-6">
                            <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Amount</div>
							<input type="number" step="0.01" class="form-control add-patient-test-price" name="test_price[]" placeholder="0.00" autocomplete="off" id="field_1053">
						</div>
						<div class="col-md-3 col-6">
                            <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Discount</div>
							<input type="number" step="0.01" class="form-control add-patient-test-discount" name="test_discount[]" value="0.00" autocomplete="off" id="field_1054">
						</div>
						<div class="col-md-1 col-12 text-center pt-md-0 pt-2">
							<button type="button" class="btn btn-success btn-sm btn-add-add-test-row" style="height: 38px; width: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i class="fa fa-plus"></i></button>
						</div>
					</div>
				</div>
				<div class="bg-primary-light p-3 rounded mb-3 text-end">
					<h5 class="mb-0 fw-bold">Net Payable: ₹<span id="add-patient-net-payable">0.00</span></h5>
				</div>

				<div class="form-group">
					<label for="field_1055" class="form-label">Address</label>
					<textarea rows="2" class="form-control" name="address" placeholder="Address" autocomplete="off" id="field_1055"></textarea>
				</div>

			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
			<button type="button" class="btn-aw-primary" id="btn-save-patient"><i class="fa fa-check"></i> Save Patient</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- View Patient Modal -->
  <div class="modal fade" id="modal-view-patient" tabindex="-1" aria-labelledby="viewPatientLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
      <div class="modal-content">

        <!-- Gradient Header -->
        <div class="vp-header">
          <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="vp-avatar" id="vp-avatar">P</div>
          <div class="vp-name" id="view-full-name">Patient Name</div>
          <div class="vp-meta">
            <span class="vp-id-badge" id="view-patient-id">#0000</span>
            <span>·</span>
            <span id="view-gender">—</span>
            <span>·</span>
            <span id="view-age">—</span>
          </div>
        </div>

        <!-- Body -->
        <div class="vp-body">
          <!-- Status badge floated up -->
          <div class="vp-status-row">
            <div id="view-status-badge"></div>
          </div>

          <!-- Info grid -->
          <div class="vp-grid">
            <div class="vp-field">
              <div class="vp-field-label"><i class="fa fa-phone"></i> Phone</div>
              <div class="vp-field-value" id="view-phone">—</div>
            </div>
            <div class="vp-field">
              <div class="vp-field-label"><i class="fa fa-envelope"></i> Email</div>
              <div class="vp-field-value" id="view-email">—</div>
            </div>
            <div class="vp-field">
              <div class="vp-field-label"><i class="fa fa-user-doctor"></i> Ref. Doctor</div>
              <div class="vp-field-value" id="view-reference-dr">—</div>
            </div>
            <div class="vp-field">
              <div class="vp-field-label"><i class="fa fa-calendar"></i> Registered</div>
              <div class="vp-field-value" id="view-created-at">—</div>
            </div>
            <div class="vp-field vp-full">
              <div class="vp-field-label"><i class="fa fa-location-dot"></i> Address</div>
              <div class="vp-field-value" id="view-address">—</div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade modal-aw" id="modal-edit-patient" tabindex="-1" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title"><i class="fa fa-user-pen me-2"></i>Edit Patient</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-patient">
				<input type="hidden" id="edit-id" name="id">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-patient-id" class="form-label text-primary">Patient ID</label>
							<input type="text" class="form-control" id="edit-patient-id" name="patient_id" placeholder="Patient ID" readonly autocomplete="off">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-phone" class="form-label">Phone No</label>
							<input type="text" class="form-control" id="edit-phone" name="phone" placeholder="Phone No" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-first-name" class="form-label">First Name</label>
							<input type="text" class="form-control" id="edit-first-name" name="first_name" placeholder="First Name" autocomplete="off">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-last-name" class="form-label">Last Name</label>
							<input type="text" class="form-control" id="edit-last-name" name="last_name" placeholder="Last Name" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-gender" class="form-label">Gender</label>
							<select class="form-select" id="edit-gender" name="gender" autocomplete="off">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
								<option value="Other">Other</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-age" class="form-label">Age</label>
							<div class="input-group flex-nowrap">
								<input type="number" class="form-control" id="edit-age" name="age" placeholder="Age" autocomplete="off" min="0" max="150">
								<select class="form-select" id="edit-age-type" name="age_type" autocomplete="off" style="max-width: 110px;">
									<option value="Years">Years</option>
									<option value="Months">Months</option>
									<option value="Days">Days</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-email" class="form-label">Email</label>
							<input type="email" class="form-control" id="edit-email" name="email" placeholder="Email" autocomplete="new-password">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-status" class="form-label">Status</label>
							<select class="form-select" id="edit-status" name="status" autocomplete="off">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="field_1056" class="form-label">Reference Dr.</label>
							<div class="reference-dr-container">
								<input type="hidden" class="reference-dr-value" name="reference_dr" value="" id="field_1056">
								<div class="input-group flex-nowrap reference-dr-input-group">
									<select class="form-select reference-dr-select" id="edit-reference-dr" autocomplete="off" name="name_1057">
										<option value="">-- Select Doctor --</option>
									</select>
									<button type="button" class="btn btn-success btn-add-doctor" title="Add New"><i class="fa fa-plus"></i></button>
									<button type="button" class="btn btn-warning btn-edit-doctor" title="Edit Selected"><i class="fa fa-edit"></i></button>
									<button type="button" class="btn btn-danger btn-delete-doctor" title="Delete Selected"><i class="fa fa-trash"></i></button>
								</div>
								<div class="reference-dr-custom-wrap" style="display:none;">
									<div class="input-group">
										<input type="text" class="form-control reference-dr-custom-input" placeholder="Enter custom doctor name" autocomplete="off" id="field_1058" name="name_1059">
										<button type="button" class="btn btn-outline-secondary btn-back-to-dr-select" title="Back to dropdown" style="font-size:12px;"><i class="fa fa-list"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="edit-payment-method" class="form-label">Payment Mode</label>
							<select class="form-select" name="payment_method" id="edit-payment-method" autocomplete="off">
								<option value="">-- Select Payment Mode --</option>
								<option value="Cash">Cash</option>
								<option value="Card">Card</option>
								<option value="UPI">UPI</option>
								<option value="Net Banking">Net Banking</option>
							</select>
						</div>
					</div>
				</div>

				<h5 class="mt-4 mb-2 text-primary fw-bold"><i class="fa fa-flask me-2"></i>Select Tests & Financial Details</h5>
				
                <div class="d-none d-md-flex row fw-bold text-muted mb-2 px-3" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <div class="col-md-5">Test Name</div>
                    <div class="col-md-3">Amount (₹)</div>
                    <div class="col-md-3">Discount (₹)</div>
                    <div class="col-md-1 text-center">Action</div>
                </div>

				<div id="edit-patient-tests-container" class="mb-3">
					<!-- Dynamically loaded from JS -->
				</div>
				<div class="bg-primary-light p-3 rounded mb-3 text-end">
					<h5 class="mb-0 fw-bold">Net Payable: ₹<span id="edit-patient-net-payable">0.00</span></h5>
				</div>

				<div class="form-group">
					<label for="edit-address" class="form-label">Address</label>
					<textarea rows="2" class="form-control" id="edit-address" name="address" placeholder="Address" autocomplete="off"></textarea>
				</div>

			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
			<button type="button" class="btn-aw-primary" id="btn-update-patient"><i class="fa fa-check"></i> Update Patient</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal center-modal fade" id="modal-delete-patient" tabindex="-1" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Delete Patient</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body text-center">
			<i class="fa fa-warning fa-4x text-danger mb-15"></i>
			<h4 class="mb-10">Confirm Deletion</h4>
			<p>Are you sure you want to delete this patient record? This action cannot be undone and will remove all associated history.</p>
			<input type="hidden" id="delete-id" name="name_1060">
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-danger float-end" id="btn-confirm-delete">Delete Permanently</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Quick Book Test Modal -->
  <div class="modal center-modal fade" id="modal-patient-book-test" tabindex="-1" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header bg-primary">
			<h5 class="modal-title text-white">Book Lab Test for <span id="book-test-patient-name"></span></h5>
			<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-quick-book">
				<input type="hidden" name="patient_id" id="quick-book-patient-id">
				<div class="form-group">
					<label for="quick-book-test-name" class="form-label">Test Name</label>
					<select class="form-select" name="test_name" id="quick-book-test-name" required autocomplete="off">
						<option value="">-- Select Test --</option>
						@foreach($labTests as $test)
							<option value="{{ $test->name }}" data-price="{{ $test->price }}">{{ $test->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="quick-book-price" class="form-label">Price (₹)</label>
							<input type="number" step="0.01" class="form-control" name="test_price" id="quick-book-price" required autocomplete="off">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="quick-book-discount" class="form-label text-danger">Discount (₹)</label>
							<input type="number" step="0.01" class="form-control" name="test_discount" id="quick-book-discount" value="0.00" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="text-end mb-2 pe-3">
					<span style="font-size:16px; font-weight:700; color:var(--text-dark);">Net Payable: ₹<span id="quick-book-net-payable">0.00</span></span>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="field_1061" class="form-label">Date</label>
							<input type="date" class="form-control" name="appointment_date" value="{{ date('Y-m-d') }}" required autocomplete="off" id="field_1061">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="field_1062" class="form-label">Status</label>
							<select class="form-select" name="status" autocomplete="off" id="field_1062">
								<option value="Scheduled">Scheduled</option>
								<option value="Pending">Pending</option>
								<option value="Completed">Completed</option>
							</select>
						</div>
					</div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="btn-quick-book-save">Confirm Booking</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Add Doctor Modal -->
  <div class="modal fade modal-aw" id="modal-add-doctor" tabindex="-1" style="z-index: 1060;" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title"><i class="fa fa-user-md me-2"></i>Add New Doctor</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-doctor">
				<div class="form-group">
					<label for="field_1063" class="form-label-aw">Doctor Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control-aw" name="name" required placeholder="e.g. Dr. John Doe" autocomplete="off" id="field_1063">
				</div>
				<div class="form-group mt-3">
					<label for="field_1064" class="form-label-aw">Qualification</label>
					<input type="text" class="form-control-aw" name="qualification" placeholder="e.g. MBBS, MD" autocomplete="off" id="field_1064">
				</div>
				<div class="form-group mt-3">
					<label for="field_1065" class="form-label-aw">Phone No</label>
					<input type="text" class="form-control-aw" name="phone" placeholder="Phone Number" autocomplete="off" id="field_1065">
				</div>
				<div class="form-group mt-3">
					<label for="field_1066" class="form-label-aw">Email</label>
					<input type="email" class="form-control-aw" name="email" placeholder="Email Address" autocomplete="new-password" id="field_1066">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn-aw-primary" id="btn-save-doctor"><i class="fa fa-check"></i> Save Doctor</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Add Test Modal -->
  <div class="modal fade modal-aw" id="modal-add-test" tabindex="-1" style="z-index: 1060;" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title"><i class="fa fa-flask me-2"></i>Add New Test</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-test">
                @csrf
				<div class="form-group">
					<label for="field_1001" class="form-label-aw">Test Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control-aw" name="name" required placeholder="e.g. CBC" autocomplete="off" id="field_1001">
				</div>
				<div class="form-group mt-3">
					<label for="field_1002" class="form-label-aw">Price</label>
					<input type="number" step="0.01" class="form-control-aw" name="price" placeholder="e.g. 500" autocomplete="off" id="field_1002">
				</div>
				<div class="form-group mt-3">
					<label for="field_add_test_payment_method" class="form-label-aw">Payment Mode</label>
					<select class="form-select" name="payment_method" autocomplete="off" id="field_add_test_payment_method">
						<option value="">-- Select Payment Mode --</option>
						<option value="Cash">Cash</option>
						<option value="Card">Card</option>
						<option value="UPI">UPI</option>
						<option value="Net Banking">Net Banking</option>
					</select>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn-aw-primary" id="btn-save-test"><i class="fa fa-check"></i> Save Test</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Test Modal -->
  <div class="modal fade modal-aw" id="modal-edit-test" tabindex="-1" style="z-index: 1060;" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title"><i class="fa fa-flask me-2"></i>Edit Test</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-test">
                @csrf
				<input type="hidden" name="test_id" id="edit-test-id">
				<div class="form-group">
					<label for="edit-test-name" class="form-label-aw">Test Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control-aw" name="name" id="edit-test-name" required autocomplete="off">
				</div>
				<div class="form-group mt-3">
					<label for="edit-test-price" class="form-label-aw">Price</label>
					<input type="number" step="0.01" class="form-control-aw" name="price" id="edit-test-price" autocomplete="off">
				</div>
				<div class="form-group mt-3">
					<label for="edit-test-payment-method" class="form-label-aw">Payment Mode</label>
					<select class="form-select" name="payment_method" id="edit-test-payment-method" autocomplete="off">
						<option value="">-- Select Payment Mode --</option>
						<option value="Cash">Cash</option>
						<option value="Card">Card</option>
						<option value="UPI">UPI</option>
						<option value="Net Banking">Net Banking</option>
					</select>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn-aw-primary" id="btn-update-test"><i class="fa fa-check"></i> Update Changes</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Doctor Modal -->
  <div class="modal fade modal-aw" id="modal-edit-doctor" tabindex="-1" style="z-index: 1060;" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title"><i class="fa fa-user-md me-2"></i>Edit Doctor</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-doctor">
				<input type="hidden" name="doctor_id" id="edit-doc-id">
				<div class="form-group">
					<label for="edit-doc-name" class="form-label-aw">Doctor Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control-aw" name="name" id="edit-doc-name" required autocomplete="off">
				</div>
				<div class="form-group mt-3">
					<label for="edit-doc-qualification" class="form-label-aw">Qualification</label>
					<input type="text" class="form-control-aw" name="qualification" id="edit-doc-qualification" autocomplete="off">
				</div>
				<div class="form-group mt-3">
					<label for="edit-doc-phone" class="form-label-aw">Phone No</label>
					<input type="text" class="form-control-aw" name="phone" id="edit-doc-phone" autocomplete="off">
				</div>
				<div class="form-group mt-3">
					<label for="edit-doc-email" class="form-label-aw">Email</label>
					<input type="email" class="form-control-aw" name="email" id="edit-doc-email" autocomplete="new-password">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn-aw-primary" id="btn-update-doctor"><i class="fa fa-check"></i> Update Changes</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- JavaScript -->
  @push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <script>
	  $(document).ready(function() {
		  // Set CSRF Token for all AJAX requests
		  $.ajaxSetup({
			  headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			  }
		  });

          // Select2 initialization function
          function initDynamicSelect2() {
              // Revert all select elements inside the patient modals to standard native HTML selects
              $('#modal-add-patient select, #modal-edit-patient select, #modal-patient-book-test select').each(function() {
                  if ($(this).hasClass('select2-hidden-accessible')) {
                      $(this).select2('destroy');
                  }
              });
          }

          $(document).on('shown.bs.modal', '#modal-add-patient, #modal-edit-patient, #modal-patient-book-test', function() {
              initDynamicSelect2();
          });

          initDynamicSelect2();

		  // Initialize DataTables for Patients with custom search wrapper
		  var patientTable = $('#patient-table').DataTable({
			  dom: "<'row mb-3'<'col-sm-12 col-md-6'l>>" +
				   "<'row'<'col-sm-12'tr>>" +
				   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			  pageLength: 10,
			  lengthMenu: [5, 10, 25, 50, 100],
			  ordering: false,
			  language: {
				  lengthMenu: "Show _MENU_ records",
				  info: "Showing _START_ to _END_ of _TOTAL_ patients",
				  infoEmpty: "Showing 0 to 0 of 0 patients",
				  infoFiltered: "(filtered from _MAX_ total patients)",
				  emptyTable: "No patients found in the database.",
				  paginate: {
					  previous: "<i class='fa fa-angle-left'></i>",
					  next: "<i class='fa fa-angle-right'></i>"
				  }
			  }
		  });
		  $("#patient-search").on("keyup", function() {
			  patientTable.search($(this).val()).draw();
		  });

		  // Generate Invoice
		  $(document).on('click', '.btn-invoice', function(e) {
			  e.preventDefault();
			  let id = $(this).data('id');
			  let btn = $(this);
			  let originalHtml = btn.html();
			  btn.html('<i class="fa fa-spinner fa-spin"></i>').addClass('disabled');

			  $.get("/patients/" + id, function(patient) {
				  // Fetch appointments for this patient to get test details
				  $.get("{{ route('appointments') }}", function(appointments) {
					  const patientAppointments = appointments.filter(a => a.patient_id == patient.id);
					  
					  const { jsPDF } = window.jspdf;
					  const doc = new jsPDF();

					  // --- Header ---
					  doc.setFont("helvetica", "bold");
					  doc.setFontSize(22);
					  doc.setTextColor(0);
					  doc.text("AWWAL LAB", 20, 20);
					  
					  doc.setFontSize(10);
					  doc.setFont("helvetica", "normal");
					  doc.setTextColor(0); // Black
					  doc.text("A Muhammed's Complex Chenayikunnu Road Jn.", 20, 26);
					  doc.text("PATHAPPIRIYAM, Vayanasala", 20, 31);
					  doc.text("Ph: 7034 250 209, 7559 049 948 | Email: awwallabppm@gmail.com", 20, 36);
					  doc.text("Website: https://awwallabs.in/", 20, 41);
					  
					  doc.setDrawColor(0);
					  doc.setLineWidth(0.5);
					  doc.line(20, 46, 190, 46);

					  // --- Invoice Info ---
					  doc.setFontSize(10);
                      doc.setTextColor(0);
					  doc.setFont("helvetica", "bold");
					  doc.text("INVOICE DETAILS", 140, 26);
					  doc.setFont("helvetica", "normal");
					  const now = new Date();
					  const dateStr = now.toLocaleDateString();
					  const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
					  doc.text(`Date: ${dateStr} ${timeStr}`, 140, 32);
					  doc.text(`Invoice No: INV-${patient.id}${Math.floor(Math.random() * 1000)}`, 140, 38);

					  // --- Patient Info ---
					  doc.setFont("helvetica", "bold");
					  doc.text("PATIENT INFORMATION:", 20, 54);
					  
					  doc.setFontSize(9);
					  // Column 1
					  doc.setFont("helvetica", "bold"); doc.text("Name:", 20, 60);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.first_name} ${patient.last_name}`, 45, 60);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Patient ID:", 20, 65);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.patient_id.replace('#P-', '').replace('#', '')}`, 45, 65);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Age / Gender:", 20, 70);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.age} ${patient.age_type || 'Years'} / ${patient.gender}`, 45, 70);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Phone:", 20, 75);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.phone || 'N/A'}`, 45, 75);

					  // Column 2
					  doc.setFont("helvetica", "bold"); doc.text("Ref. Dr:", 105, 60);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.reference_dr || 'Self'}`, 135, 60);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Payment Mode:", 105, 65);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.payment_method || 'N/A'}`, 135, 65);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Address:", 105, 70);
					  doc.setFont("helvetica", "normal"); 
                      let addressText = patient.address || 'N/A';
                      // Split address if it's too long
                      let splitAddress = doc.splitTextToSize(addressText, 55);
                      doc.text(splitAddress, 135, 70);

					  // --- Table ---
					  const tableData = patientAppointments.map((app, index) => [
						  index + 1,
						  app.test_name || "General Lab Test",
						  app.appointment_date,
						  `${parseFloat(app.test_price || 0).toFixed(2)}`
					  ]);

					  if (tableData.length === 0) {
						  tableData.push([1, "Consultation/Registration", new Date().toISOString().split('T')[0], "0.00"]);
					  }

					  doc.autoTable({
						  startY: 92,
						  head: [['S.No', 'Description of Services/Tests', 'Date', 'Amount (INR)']],
						  body: tableData,
						  theme: 'grid',
						  headStyles: { 
							  fillColor: [240, 240, 240], 
							  textColor: [0, 0, 0], 
							  fontStyle: 'bold',
							  lineWidth: 0.1,
							  lineColor: [0, 0, 0]
						  },
						  styles: { 
							  fontSize: 9,
							  cellPadding: 3,
							  lineColor: [0, 0, 0],
							  lineWidth: 0.1
						  }
					  });

					  const finalY = doc.lastAutoTable.finalY + 10;
					  const subTotal = patientAppointments.reduce((sum, app) => sum + parseFloat(app.test_price || 0), 0);
					  const totalDiscount = patientAppointments.reduce((sum, app) => sum + parseFloat(app.discount || 0), 0);
					  const netPayable = subTotal - totalDiscount;

					  // --- Summary ---
					  doc.setFontSize(10);
					  doc.setFont("helvetica", "normal");
					  doc.text("Sub-Total:", 120, finalY);
					  doc.text(`${subTotal.toFixed(2)}`, 190, finalY, { align: "right" });
					  
					  doc.text("Total Discount:", 120, finalY + 6);
					  doc.text(`${totalDiscount.toFixed(2)}`, 190, finalY + 6, { align: "right" });

					  doc.setFont("helvetica", "bold");
					  doc.text("NET PAYABLE AMOUNT:", 120, finalY + 14);
					  doc.setFontSize(12);
					  doc.setTextColor(0); // Black
					  doc.text(`${netPayable.toFixed(2)}`, 190, finalY + 14, { align: "right" });

					  // --- Footer / Signatures ---
					  doc.setFontSize(9);
					  doc.setFont("helvetica", "normal");
					  doc.text("Terms & Conditions:", 20, 250);
					  doc.text("1. This is a computer generated invoice and does not require a physical signature.", 20, 256);
					  doc.text("2. Please preserve this invoice for future reference and report collection.", 20, 261);

					  doc.line(140, 270, 190, 270);
					  doc.text("Authorized Signatory", 150, 276);

					  doc.save(`Invoice_${patient.first_name}_${patient.patient_id.replace('#P-', '').replace('#', '')}.pdf`);
					  btn.html(originalHtml).removeClass('disabled');
				  }).fail(function() {
					  alert("Failed to fetch appointment records.");
					  btn.html(originalHtml).removeClass('disabled');
				  });
			  }).fail(function() {
				  alert("Failed to fetch patient data.");
				  btn.html(originalHtml).removeClass('disabled');
			  });
		  });

		  // Clear Add Form when modal opens
		  $('#modal-add-patient').on('show.bs.modal', function () {
			  $('#form-add-patient')[0].reset();
		  });

		  // Fetch Doctor Suggestions
		  function fetchDoctorSuggestions(selectedValue = null) {
			  $.get("{{ route('doctors.suggestions') }}", function(data) {
				  let options = '<option value="">-- Select Doctor --</option><option value="Self" data-id="">Self</option>';
				  data.forEach(function(doctor) {
					  let phone = doctor.phone ? doctor.phone : '';
					  let email = doctor.email ? doctor.email : '';
					  let qual = doctor.qualification ? doctor.qualification : '';
					  options += '<option value="' + doctor.name + '" data-id="'+doctor.id+'" data-phone="'+phone+'" data-email="'+email+'" data-qualification="'+qual+'">' + doctor.name + '</option>';
				  });
				  options += '<option value="__custom__">✏️ Custom (type below)</option>';
				  $('.reference-dr-select').html(options);
                   if (selectedValue) {
                       $('.reference-dr-select').val(selectedValue).trigger('change');
                   } else {
                       $('.reference-dr-select').trigger('change');
                   }
			  });
		  }

          $(document).on('click', '.btn-add-doctor', function() {
              $('#modal-add-doctor').modal('show');
          });

          $(document).on('click', '.btn-edit-doctor', function() {
              let select = $(this).siblings('.reference-dr-select');
              let selectedOption = select.find('option:selected');
              let docId = selectedOption.data('id');
              
              if (!docId) {
                  alert('Please select a valid doctor to edit.');
                  return;
              }
              
              $('#edit-doc-id').val(docId);
              $('#edit-doc-name').val(selectedOption.val());
              $('#edit-doc-qualification').val(selectedOption.data('qualification'));
              $('#edit-doc-phone').val(selectedOption.data('phone'));
              $('#edit-doc-email').val(selectedOption.data('email'));
              $('#modal-edit-doctor').modal('show');
          });

          $(document).on('click', '.btn-delete-doctor', function() {
              let select = $(this).siblings('.reference-dr-select');
              let selectedOption = select.find('option:selected');
              let docId = selectedOption.data('id');
              
              if (!docId) {
                  alert('Please select a valid doctor to delete.');
                  return;
              }
              
              if (confirm('Are you sure you want to delete ' + selectedOption.val() + '?')) {
                  $.ajax({
                      url: "/doctors/" + docId,
                      type: 'DELETE',
                      success: function(response) {
                          alert(response.success);
                          fetchDoctorSuggestions();
                      },
                      error: function(xhr) {
                          alert('Error deleting doctor.');
                      }
                  });
              }
          });

          $('#btn-save-doctor').click(function() {
              let btn = $(this);
              if (btn.prop('disabled')) return;
              
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...');
              let formData = $('#form-add-doctor').serialize();
              
              $.post("{{ route('doctors.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-doctor').modal('hide');
                  $('#form-add-doctor')[0].reset();
                  fetchDoctorSuggestions(response.doctor.name);
                  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Save Doctor');
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to save doctor.'));
                  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Save Doctor');
              });
          });

          $('#btn-update-doctor').click(function() {
              let btn = $(this);
              if (btn.prop('disabled')) return;
              
              let docId = $('#edit-doc-id').val();
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');
              let formData = $('#form-edit-doctor').serialize();
              
              $.ajax({
                  url: "/doctors/" + docId,
                  type: 'PUT',
                  data: formData,
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-doctor').modal('hide');
                      fetchDoctorSuggestions(response.doctor.name);
                      btn.prop('disabled', false).html('<i class="fa fa-check"></i> Update Changes');
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to update doctor.'));
                      btn.prop('disabled', false).html('<i class="fa fa-check"></i> Update Changes');
                  }
              });
          });

		  fetchDoctorSuggestions();

		  // Save Patient
		  $('#btn-save-patient').click(function() {
			  let formData = $('#form-add-patient').serialize();
			  $.post("{{ route('patients.store') }}", formData, function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
				  if (xhr.status === 422) {
					  let errors = xhr.responseJSON.errors;
					  let errorMsg = "Validation Errors:\n";
					  $.each(errors, function(key, value) {
						  errorMsg += "- " + value[0] + "\n";
					  });
					  alert(errorMsg);
				  } else {
					  alert("Something went wrong. Please try again.");
				  }
			  });
		  });

		  // Book Appointment Quick Feature
		  $(document).on('click', '.btn-book-appointment', function() {
			  $('#quick-book-patient-id').val($(this).data('id'));
			  $('#book-test-patient-name').text($(this).data('name'));
			  $('#form-quick-book')[0].reset();
			  $('#quick-book-net-payable').text('0.00');
		  });

		  $('#quick-book-test-name').change(function() {
			  let price = $(this).find(':selected').data('price');
			  $('#quick-book-price').val(price ? parseFloat(price).toFixed(2) : '');
			  calculateQuickBookNet();
		  });

		  $('#quick-book-price, #quick-book-discount').on('input', calculateQuickBookNet);

		  function calculateQuickBookNet() {
			  let price = parseFloat($('#quick-book-price').val()) || 0;
			  let discount = parseFloat($('#quick-book-discount').val()) || 0;
			  $('#quick-book-net-payable').text((price - discount).toFixed(2));
		  }

		  $('#btn-quick-book-save').click(function() {
			  let btn = $(this);
			  btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Booking...').addClass('disabled');
			  $.post("{{ route('appointments.store') }}", $('#form-quick-book').serialize(), function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function() {
				  alert("Error booking test.");
				  btn.html('Confirm Booking').removeClass('disabled');
			  });
		  });

		  // View Patient
		  $(document).on('click', '.btn-view', function() {
			  let id = $(this).data('id');
			  $.get("/patients/" + id, function(data) {
				  // Name & meta
				  let fullName = ((data.first_name || '') + ' ' + (data.last_name || '')).trim();
				  $('#view-full-name').text(fullName || 'Unknown');
				  $('#view-patient-id').text(data.patient_id ? data.patient_id.replace('#P-','').replace('#','') : '—');
				  $('#view-gender').text(data.gender || '—');
				  $('#view-age').text(data.age ? data.age + ' Yrs' : '—');

				  // Fields
				  $('#view-phone').text(data.phone || '—');
				  $('#view-email').text(data.email || '—');
				  $('#view-reference-dr').text(data.reference_dr || 'Self');
				  $('#view-address').text(data.address || '—');
				  $('#view-created-at').text(data.created_at ? data.created_at.substring(0,10) : '—');

				  // Status badge
				  let isActive = data.status && data.status.toLowerCase() === 'active';
				  $('#view-status-badge').html(isActive
					  ? '<span class="status-dot-badge active"><span class="status-dot active"></span>Active</span>'
					  : '<span class="status-dot-badge inactive"><span class="status-dot inactive"></span>Inactive</span>');

				  // Avatar initials (uses stylesheet for high-contrast white layout)
				  let fi = data.first_name ? data.first_name.trim().charAt(0).toUpperCase() : '';
				  let li = data.last_name ? data.last_name.trim().charAt(0).toUpperCase() : '';
				  let initials = (fi + li) || 'P';
				  $('#vp-avatar').text(initials);

				  $('#modal-view-patient').modal('show');
			  });
		  });

		  // Edit Patient (Fetch Data)
		  $(document).on('click', '.btn-edit', function() {
			  let id = $(this).data('id');
			  $.get("/patients/" + id, function(data) {
				  $('#edit-id').val(data.id);
				  $('#edit-patient-id').val(data.patient_id);
				  $('#edit-first-name').val(data.first_name);
				  $('#edit-last-name').val(data.last_name);
				  $('#edit-gender').val(data.gender);
				  $('#edit-age').val(data.age);
				  $('#edit-age-type').val(data.age_type || 'Years');
				  $('#edit-phone').val(data.phone);
				  $('#edit-email').val(data.email);
				  let refDr = data.reference_dr || '';
				  let refDrSelect = $('#edit-reference-dr');
				  let isCustomDr = refDr !== '' && !refDrSelect.find(`option[value="${refDr}"]`).length;
				  
				  if (isCustomDr) {
					  refDrSelect.val('__custom__').trigger('change').closest('.reference-dr-input-group').hide();
					  refDrSelect.closest('.reference-dr-container').find('.reference-dr-custom-wrap').show();
					  refDrSelect.closest('.reference-dr-container').find('.reference-dr-custom-input').val(refDr);
				  } else {
					  refDrSelect.val(refDr).trigger('change').closest('.reference-dr-input-group').show();
					  refDrSelect.closest('.reference-dr-container').find('.reference-dr-custom-wrap').hide();
				  }
				  refDrSelect.closest('.reference-dr-container').find('.reference-dr-value').val(refDr);
				  $('#edit-status').val(data.status).trigger('change');
				  $('#edit-payment-method').val(data.payment_method || '').trigger('change');
				  $('#edit-address').val(data.address);

                  // Collect known test names from server (rendered by Blade)
                  const knownTestNames = [
                      @foreach($labTests as $test)
                          '{{ addslashes($test->name) }}',
                      @endforeach
                  ];

                  // Populate existing appointments
                  let testRowsHtml = '';
                  if (data.appointments && data.appointments.length > 0) {
                      data.appointments.forEach(function(app, index) {
                          let testName = app.test_name || '';
                          let isCustom = testName !== '' && !knownTestNames.includes(testName);
                          let optionsHtml = `<option value="">-- Select Test --</option>`;
                          @foreach($labTests as $test)
                              optionsHtml += `<option value="{{ $test->name }}" data-id="{{ $test->id }}" data-price="{{ $test->price }}" data-payment_method="{{ $test->payment_method }}" ${!isCustom && app.test_name == '{{ $test->name }}' ? 'selected' : ''}>{{ $test->name }}</option>`;
                          @endforeach
                          optionsHtml += `<option value="__custom__">✏️ Custom (type below)</option>`;

                          testRowsHtml += `
                            <div class="row test-row mb-2 align-items-center">
                                <input type="hidden" name="appointment_id[]" value="${app.id}" id="field_1067">
                                <div class="col-md-5 col-12">
                                    <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Test Name</div>
                                    <input type="hidden" class="test-name-value" name="test_name[]" value="${testName}" id="field_1068">
                                    <div class="input-group flex-nowrap" ${isCustom ? 'style="display:none;"' : ''}>
                                        <select class="form-select edit-patient-test-name test-name-select" autocomplete="off" id="field_1069" name="name_1070">
                                            ${optionsHtml}
                                        </select>
                                        <button type="button" class="btn btn-success btn-add-test" style="background-color: #d1fae5; color: #059669; border-color: #cbd5e1;" title="Add New Test"><i class="fa fa-plus"></i></button>
                                        <button type="button" class="btn btn-primary btn-edit-test" style="background-color: #dbeafe; color: #2563eb; border-color: #cbd5e1;" title="Edit Selected Test"><i class="fa fa-edit"></i></button>
                                    </div>
                                    <div class="test-name-custom-wrap" ${isCustom ? '' : 'style="display:none;"'}>
                                        <div class="input-group">
                                            <input type="text" class="form-control test-name-custom-input" placeholder="Enter custom test name" value="${isCustom ? testName : ''}" autocomplete="off" id="field_1071" name="name_1072">
                                            <button type="button" class="btn btn-outline-secondary btn-back-to-select" title="Back to dropdown" style="font-size:12px;"><i class="fa fa-list"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Amount</div>
                                    <input type="number" step="0.01" class="form-control edit-patient-test-price" name="test_price[]" value="${parseFloat(app.test_price).toFixed(2)}" autocomplete="off" id="field_1073">
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Discount</div>
                                    <input type="number" step="0.01" class="form-control edit-patient-test-discount" name="test_discount[]" value="${parseFloat(app.discount).toFixed(2)}" autocomplete="off" id="field_1074">
                                </div>
                                <div class="col-md-1 col-12 text-center pt-md-0 pt-2">
                                    ${index === 0 ? '<button type="button" class="btn btn-success btn-sm btn-edit-add-test-row" style="height: 38px; width: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i class="fa fa-plus"></i></button>' : '<button type="button" class="btn btn-danger btn-sm btn-remove-test-row" style="height: 38px; width: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i class="fa fa-trash"></i></button>'}
                                </div>
                            </div>`;
                      });
                  } else {
                      // Default empty row if no appointments found
                      let emptyOptions = `<option value="" selected>-- Select Test (Optional) --</option>`;
                      @foreach($labTests as $test)
                          emptyOptions += `<option value="{{ $test->name }}" data-id="{{ $test->id }}" data-price="{{ $test->price }}" data-payment_method="{{ $test->payment_method }}">{{ $test->name }}</option>`;
                      @endforeach
                      emptyOptions += `<option value="__custom__">✏️ Custom (type below)</option>`;

                      testRowsHtml = `
                        <div class="row test-row mb-2 align-items-center">
                            <div class="col-md-5 col-12">
                                <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Test Name</div>
                                <input type="hidden" class="test-name-value" name="test_name[]" value="" id="field_1075">
                                <div class="input-group flex-nowrap">
                                    <select class="form-select edit-patient-test-name test-name-select" autocomplete="off" id="field_1076" name="name_1077">
                                        ${emptyOptions}
                                    </select>
                                    <button type="button" class="btn btn-success btn-add-test" style="background-color: #d1fae5; color: #059669; border-color: #cbd5e1;" title="Add New Test"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-primary btn-edit-test" style="background-color: #dbeafe; color: #2563eb; border-color: #cbd5e1;" title="Edit Selected Test"><i class="fa fa-edit"></i></button>
                                </div>
                                <div class="test-name-custom-wrap" style="display:none;">
                                    <div class="input-group">
                                        <input type="text" class="form-control test-name-custom-input" placeholder="Enter custom test name" autocomplete="off" id="field_1078" name="name_1079">
                                        <button type="button" class="btn btn-outline-secondary btn-back-to-select" title="Back to dropdown" style="font-size:12px;"><i class="fa fa-list"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Amount</div>
                                <input type="number" step="0.01" class="form-control edit-patient-test-price" name="test_price[]" placeholder="0.00" autocomplete="off" id="field_1080">
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Discount</div>
                                <input type="number" step="0.01" class="form-control edit-patient-test-discount" name="test_discount[]" placeholder="0.00" value="0.00" autocomplete="off" id="field_1081">
                            </div>
                            <div class="col-md-1 col-12 text-center pt-md-0 pt-2">
                                <button type="button" class="btn btn-success btn-sm btn-edit-add-test-row" style="height: 38px; width: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>`;
                  }

                  $('#edit-patient-tests-container').html(testRowsHtml);
                  initDynamicSelect2();
                  calculateNetPayable('edit');
			  });
		  });

		  // Dynamic row logic for Add Patient modal
		  const addTestRowTemplate = `
			<div class="row test-row mb-2 align-items-center">
				<div class="col-md-5 col-12">
					<div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Test Name</div>
					<input type="hidden" class="test-name-value" name="test_name[]" value="" id="field_1082">
					<div class="input-group flex-nowrap">
						<select class="form-select add-patient-test-name test-name-select" autocomplete="off" id="field_1083" name="name_1084">
							<option value="">-- Select Test --</option>
							@foreach($labTests as $test)
								<option value="{{ $test->name }}" data-id="{{ $test->id }}" data-price="{{ $test->price }}" data-payment_method="{{ $test->payment_method }}">{{ $test->name }}</option>
							@endforeach
							<option value="__custom__">✏️ Custom (type below)</option>
						</select>
						<button type="button" class="btn btn-success btn-add-test" style="background-color: #d1fae5; color: #059669; border-color: #cbd5e1;" title="Add New Test"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-primary btn-edit-test" style="background-color: #dbeafe; color: #2563eb; border-color: #cbd5e1;" title="Edit Selected Test"><i class="fa fa-edit"></i></button>
					</div>
					<div class="test-name-custom-wrap" style="display:none;">
						<div class="input-group">
							<input type="text" class="form-control test-name-custom-input" placeholder="Enter custom test name" autocomplete="off" id="field_1085" name="name_1086">
							<button type="button" class="btn btn-outline-secondary btn-back-to-select" title="Back to dropdown" style="font-size:12px;"><i class="fa fa-list"></i></button>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Amount</div>
					<input type="number" step="0.01" class="form-control add-patient-test-price" name="test_price[]" placeholder="0.00" autocomplete="off" id="field_1087">
				</div>
				<div class="col-md-3 col-6">
					<div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Discount</div>
					<input type="number" step="0.01" class="form-control add-patient-test-discount" name="test_discount[]" value="0.00" autocomplete="off" id="field_1088">
				</div>
				<div class="col-md-1 col-12 text-center pt-md-0 pt-2">
					<button type="button" class="btn btn-danger btn-sm btn-add-remove-test-row" style="height: 38px; width: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i class="fa fa-trash"></i></button>
				</div>
			</div>`;

		  $(document).on('click', '.btn-add-add-test-row', function() {
			  $('#add-patient-tests-container').append(addTestRowTemplate);
              initDynamicSelect2();
		  });

		  $(document).on('click', '.btn-add-remove-test-row', function() {
			  $(this).closest('.test-row').remove();
			  calculateNetPayable('add');
		  });

		  // Dynamic row logic for Edit Patient modal
		  const editTestRowTemplate = `
			<div class="row test-row mb-2 align-items-center">
				<input type="hidden" name="appointment_id[]" value="" id="field_1089">
				<div class="col-md-5 col-12">
					<div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Test Name</div>
					<input type="hidden" class="test-name-value" name="test_name[]" value="" id="field_1090">
					<select class="form-select edit-patient-test-name test-name-select" autocomplete="off" id="field_1091" name="name_1092">
						<option value="">-- Select Test --</option>
						@foreach($labTests as $test)
							<option value="{{ $test->name }}" data-price="{{ $test->price }}" data-payment_method="{{ $test->payment_method }}">{{ $test->name }}</option>
						@endforeach
						<option value="__custom__">✏️ Custom (type below)</option>
					</select>
					<div class="test-name-custom-wrap" style="display:none;">
						<div class="input-group">
							<input type="text" class="form-control test-name-custom-input" placeholder="Enter custom test name" autocomplete="off" id="field_1093" name="name_1094">
							<button type="button" class="btn btn-outline-secondary btn-back-to-select" title="Back to dropdown" style="font-size:12px;"><i class="fa fa-list"></i></button>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Amount</div>
					<input type="number" step="0.01" class="form-control edit-patient-test-price" name="test_price[]" placeholder="0.00" autocomplete="off" id="field_1095">
				</div>
				<div class="col-md-3 col-6">
					<div class="d-md-none fw-bold fs-11 text-uppercase text-muted mb-1">Discount</div>
					<input type="number" step="0.01" class="form-control edit-patient-test-discount" name="test_discount[]" value="0.00" autocomplete="off" id="field_1096">
				</div>
				<div class="col-md-1 col-12 text-center pt-md-0 pt-2">
					<button type="button" class="btn btn-danger btn-sm btn-remove-test-row" style="height: 38px; width: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;"><i class="fa fa-trash"></i></button>
				</div>
			</div>`;

		  $(document).on('click', '.btn-edit-add-test-row', function() {
			  $('#edit-patient-tests-container').append(editTestRowTemplate);
              initDynamicSelect2();
		  });

		  $(document).on('click', '.btn-remove-test-row', function() {
			  $(this).closest('.test-row').remove();
			  calculateNetPayable('edit');
		  });

		  // ── Custom test name toggle logic ──────────────────────────────────
		  // When dropdown changes to a test, sync the hidden input; when "__custom__" selected, show text box
		  $(document).on('change', '.test-name-select', function() {
			  let val = $(this).val();
			  let row = $(this).closest('.test-row, div[class*="col-md-5"]').closest('.row, .test-row').length
						? $(this).closest('.row, .test-row') : $(this).closest('div').parent();
			  // Simpler: just search within the wrapping col-md-5 parent
			  let col = $(this).closest('[class*="col-"]');
			  if (val === '__custom__') {
                  let ig = $(this).closest('.input-group');
                  if (ig.length && ig.find('.btn-add-test').length) {
                      ig.hide();
                  } else {
                      $(this).hide();
                  }
				  col.find('.test-name-custom-wrap').show();
				  col.find('.test-name-custom-input').focus();
				  col.find('.test-name-value').val('');
			  } else {
				  col.find('.test-name-value').val(val);
				  // Auto-fill price
				  let price = $(this).find(':selected').data('price');
				  let testRow = $(this).closest('.test-row');
				  testRow.find('.add-patient-test-price, .edit-patient-test-price').val(price ? parseFloat(price).toFixed(2) : '');
				  calculateNetPayable(testRow.find('.add-patient-test-price').length ? 'add' : 'edit');
			  }
		  });

		  // Live sync custom text → hidden input
		  $(document).on('input', '.test-name-custom-input', function() {
			  $(this).closest('[class*="col-"]').find('.test-name-value').val($(this).val().trim());
		  });

		  // Back to dropdown button
		  $(document).on('click', '.btn-back-to-select', function() {
			  let col = $(this).closest('[class*="col-"]');
			  col.find('.test-name-custom-wrap').hide();
			  col.find('.test-name-custom-input').val('');
			  col.find('.test-name-value').val('');
			  let sel = col.find('.test-name-select').val('');
              let ig = sel.closest('.input-group');
              if (ig.length && ig.find('.btn-add-test').length) {
                  ig.show();
              } else {
                  sel.show();
              }
		  });
		  // ── End custom test name logic ─────────────────────────────────────

		  // ── Custom reference doctor name toggle logic ─────────────────────────
		  $(document).on('change', '.reference-dr-select', function() {
			  let val = $(this).val();
			  let container = $(this).closest('.reference-dr-container');
			  if (val === '__custom__') {
				  $(this).closest('.reference-dr-input-group').hide();
				  container.find('.reference-dr-custom-wrap').show();
				  container.find('.reference-dr-custom-input').focus();
				  container.find('.reference-dr-value').val('');
			  } else {
				  container.find('.reference-dr-value').val(val);
			  }
		  });

		  $(document).on('input', '.reference-dr-custom-input', function() {
			  $(this).closest('.reference-dr-container').find('.reference-dr-value').val($(this).val().trim());
		  });

		  $(document).on('click', '.btn-back-to-dr-select', function() {
			  let container = $(this).closest('.reference-dr-container');
			  container.find('.reference-dr-custom-wrap').hide();
			  container.find('.reference-dr-custom-input').val('');
			  container.find('.reference-dr-value').val('');
			  container.find('.reference-dr-select').val('').closest('.reference-dr-input-group').show();
		  });
		  // ── End custom reference doctor logic ─────────────────────────────────

		  // Auto-fill price and calculate net (kept for backward compat; now handled in .test-name-select change)
		  $(document).on('change', '.add-patient-test-name', function() {
			  // handled above via .test-name-select
		  });

		  $(document).on('change', '.edit-patient-test-name', function() {
			  // handled above via .test-name-select
		  });

		  $(document).on('input', '.add-patient-test-price, .add-patient-test-discount', function() {
			  calculateNetPayable('add');
		  });

		  $(document).on('input', '.edit-patient-test-price, .edit-patient-test-discount', function() {
			  calculateNetPayable('edit');
		  });

		  function calculateNetPayable(prefix) {
			  let total = 0;
			  let discount = 0;
			  
			  $(`.${prefix}-patient-test-price`).each(function() {
				  total += parseFloat($(this).val()) || 0;
			  });
			  $(`.${prefix}-patient-test-discount`).each(function() {
				  discount += parseFloat($(this).val()) || 0;
			  });
			  
			  let net = total - discount;
			  if (net < 0) net = 0;
			  
			  $(`#${prefix}-patient-net-payable`).text(net.toFixed(2));
		  }

		  // Update Patient
		  $('#btn-update-patient').click(function() {
			  let id = $('#edit-id').val();
              let btn = $(this);
              btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...').addClass('disabled');

              let formData = $('#form-edit-patient').serialize();
			  
			  $.post("/patients/update/" + id, formData, function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
				  if (xhr.status === 422) {
					  let errors = xhr.responseJSON.errors;
					  let errorMsg = "Validation Errors:\n";
					  $.each(errors, function(key, value) {
						  errorMsg += "- " + value[0] + "\n";
					  });
					  alert(errorMsg);
				  } else {
					  alert("Something went wrong. Please try again.");
				  }
			  });
		  });

		  // Delete Patient (Set ID)
		  $(document).on('click', '.btn-delete', function() {
			  $('#delete-id').val($(this).data('id'));
		  });

		  // Confirm Delete
		  $('#btn-confirm-delete').click(function() {
			  let id = $('#delete-id').val();
			  $.ajax({
				  url: "/patients/" + id,
				  type: 'DELETE',
				  success: function(response) {
					  alert(response.success);
					  location.reload();
				  }
			  });
		  });

          // --- Add / Edit Test JS ---
          let currentTestSelect = null;

          $(document).on('click', '.btn-add-test', function() {
              currentTestSelect = $(this).siblings('.test-name-select');
              $('#form-add-test')[0].reset();
              $('#modal-add-test').modal('show');
          });

          $(document).on('click', '.btn-edit-test', function() {
              currentTestSelect = $(this).siblings('.test-name-select');
              let selectedOption = currentTestSelect.find('option:selected');
              let testId = selectedOption.data('id');
              
              if (!testId || currentTestSelect.val() === '__custom__' || currentTestSelect.val() === '') {
                  alert('Please select a valid test from the dropdown to edit.');
                  return;
              }

              $('#edit-test-id').val(testId);
              $('#edit-test-name').val(selectedOption.val());
              $('#edit-test-price').val(selectedOption.data('price'));
              $('#edit-test-payment-method').val(selectedOption.data('payment_method') || '');
              $('#modal-edit-test').modal('show');
          });

          $('#btn-save-test').click(function() {
              let btn = $(this);
              let form = $('#form-add-test');
              
              if(!form[0].checkValidity()) {
                  form[0].reportValidity();
                  return;
              }

              btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

              $.ajax({
                  url: "{{ route('lab-tests.store') }}",
                  type: "POST",
                  data: form.serialize(),
                  success: function(response) {
                      if(response.success) {
                          let t = response.test;
                          let newOption = `<option value="${t.name}" data-id="${t.id}" data-price="${t.price}" data-payment_method="${t.payment_method || ''}">${t.name}</option>`;
                          
                          // Update all test selects
                          $('.test-name-select').each(function() {
                              // Insert before the Custom option
                              $(this).find('option[value="__custom__"]').before(newOption);
                          });

                          if (currentTestSelect) {
                              currentTestSelect.val(t.name).trigger('change');
                          }
                          
                          $('#modal-add-test').modal('hide');
                      }
                  },
                  error: function(xhr) {
                      alert('Error saving test.');
                  },
                  complete: function() {
                      btn.html('<i class="fa fa-check"></i> Save Test').prop('disabled', false);
                  }
              });
          });

          $('#btn-update-test').click(function() {
              let btn = $(this);
              let form = $('#form-edit-test');
              let id = $('#edit-test-id').val();
              
              if(!form[0].checkValidity()) {
                  form[0].reportValidity();
                  return;
              }

              btn.html('<i class="fa fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

              $.ajax({
                  url: "/lab-tests/update/" + id,
                  type: "POST",
                  data: form.serialize(),
                  success: function(response) {
                      if(response.success) {
                          let t = response.test;
                          
                          // Update all test selects where this test was an option
                          $('.test-name-select').each(function() {
                              let opt = $(this).find(`option[data-id="${t.id}"]`);
                              if (opt.length) {
                                  opt.val(t.name);
                                  opt.text(t.name);
                                  opt.data('price', t.price);
                                  opt.attr('data-price', t.price);
                                  opt.data('payment_method', t.payment_method);
                                  opt.attr('data-payment_method', t.payment_method);
                              }
                          });

                          if (currentTestSelect) {
                              currentTestSelect.val(t.name).trigger('change');
                          }
                          
                          $('#modal-edit-test').modal('hide');
                      }
                  },
                  error: function(xhr) {
                      alert('Error updating test.');
                  },
                  complete: function() {
                      btn.html('<i class="fa fa-check"></i> Update Changes').prop('disabled', false);
                  }
              });
          });

	  });
  </script>
  @endpush

@endsection



