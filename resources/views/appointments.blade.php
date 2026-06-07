@extends('layouts.app')
@section('title', ' | Book Appointment')
@section('page-title', 'Book Appointment')
@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-calendar-plus"></i></div>
        <div>
            <div>Laboratory Bookings</div>
            <div style="font-size:13px;font-weight:400;color:var(--text-muted);margin-top:2px;">Manage test appointments</div>
        </div>
    </div>
    <button type="button" class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-booking">
        <i class="fa fa-plus"></i> Book New Test
    </button>
</div>
<style>


    .cat-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #3b82f6;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15);
    }

    .action-btn-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .btn-icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        transition: all 0.2s ease;
        cursor: pointer;
        outline: none;
    }

    .btn-icon-circle:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .btn-icon-circle.edit:hover {
        color: #3b82f6;
        border-color: #bfdbfe;
        background: #eff6ff;
    }

    .btn-icon-circle.view:hover {
        color: #10b981;
        border-color: #a7f3d0;
        background: #ecfdf5;
    }

    .btn-icon-circle.delete:hover {
        color: #ef4444;
        border-color: #fecaca;
        background: #fef2f2;
    }

    @media (max-width: 767px) {
    
        .table-patients tbody td:last-child { border-bottom: none !important; }
        .table-patients tbody td::before { 
            content: attr(data-label); 
            font-weight: 600; 
            color: #94a3b8; 
            font-size: 12px; 
            text-transform: uppercase;
        }
        .table-patients .text-end { justify-content: center; width: 100%; border-top: 1px solid #f1f5f9 !important; margin-top: 15px; padding-top: 15px !important; }
        .action-btn-group { width: 100%; justify-content: center; gap: 16px; }
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

    /* Seamless Input Group Custom style */
    .modal-aw .input-group .form-select {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .modal-aw .input-group .btn {
        border-radius: 0 !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        font-size: 13px;
        border: 1.5px solid #cbd5e1 !important;
        border-left: none !important;
        background: #ffffff;
        color: #64748b;
        transition: all 0.2s ease;
    }

    .modal-aw .input-group .btn:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .modal-aw .input-group .btn:last-child {
        border-top-right-radius: 9px !important;
        border-bottom-right-radius: 9px !important;
    }

    .modal-aw .input-group .btn-success {
        background: #d1fae5 !important;
        color: #059669 !important;
        border-color: #cbd5e1 !important;
    }
    .modal-aw .input-group .btn-success:hover {
        background: #059669 !important;
        color: #fff !important;
        border-color: #059669 !important;
    }

    .modal-aw .input-group .btn-warning {
        background: #ffedd5 !important;
        color: #d97706 !important;
        border-color: #cbd5e1 !important;
    }
    .modal-aw .input-group .btn-warning:hover {
        background: #d97706 !important;
        color: #fff !important;
        border-color: #d97706 !important;
    }

    .modal-aw .input-group .btn-danger {
        background: #fee2e2 !important;
        color: #dc2626 !important;
        border-color: #cbd5e1 !important;
    }
    .modal-aw .input-group .btn-danger:hover {
        background: #dc2626 !important;
        color: #fff !important;
        border-color: #dc2626 !important;
    }

    /* Modal layout styling */
    .modal-aw .form-group {
        margin-bottom: 16px;
    }
</style>
<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-list" style="color:var(--primary);"></i> All Bookings</div>
        <div style="position:relative;">
            <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
            <input type="text" id="booking-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search bookings..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">

    <table class="table-modern" id="booking-table">
        <thead>
            <tr>
                <th class="d-none d-md-table-cell">Booking ID</th>
                <th>Patient Name</th>
                <th class="d-none d-lg-table-cell">Test Name</th>
                <th>Date</th>
                <th class="d-none d-sm-table-cell">Amount</th>
                <th>Status</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($appointments as $booking)
        <tr>
            <td class="d-none d-md-table-cell" data-label="SL No"><span class="badge-aw" style="background:#f1f5f9;color:#475569;font-family:monospace;font-size:12px;padding:6px 10px;border-radius:6px;border:1px solid #e2e8f0;">#B-{{ $booking->id }}</span></td>
            <td data-label="Patient Name" style="font-weight:600; color:#1e293b;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="cat-icon-box" style="width:32px;height:32px;font-size:14px;"><i class="fa fa-user"></i></div>
                    {{ $booking->patient->first_name }} {{ $booking->patient->last_name }}
                </div>
            </td>
            <td class="d-none d-lg-table-cell" data-label="Test Name" style="color:#64748b;">{{ $booking->test_name }}</td>
            <td data-label="Date" style="color:#64748b;font-size:13px;"><i class="fa fa-calendar-alt me-2" style="opacity:0.5;"></i>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('d M Y') }}</td>
            <td class="d-none d-sm-table-cell" data-label="Amount" style="font-weight:600;color:#3b82f6;">₹{{ number_format($booking->test_price, 2) }}</td>
            <td data-label="Status">
                @if($booking->status == 'Completed')
                    <span class="badge-aw" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Completed</span>
                @elseif($booking->status == 'Cancelled')
                    <span class="badge-aw" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;">Cancelled</span>
                @else
                    <span class="badge-aw" style="background:#fef9c3;color:#854d0e;border:1px solid #fef08a;">{{ $booking->status }}</span>
                @endif
            </td>
            <td class="text-end" data-label="Action">
                <div class="action-btn-group">
                    <button class="btn-icon-circle edit btn-edit" data-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#modal-edit-booking" title="Edit"><i class="fa fa-pen"></i></button>
                    <button class="btn-icon-circle delete btn-delete" data-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete-booking" title="Delete"><i class="fa fa-trash"></i></button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center" style="padding:48px;color:var(--text-muted);">
                <i class="fa fa-folder-open" style="font-size:40px;display:block;margin-bottom:12px;opacity:0.4;"></i>
                <span style="font-size:15px;">No laboratory bookings found.</span>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
        </div>
    </div>
</div>

<!-- Add Booking Modal -->
<div class="modal fade modal-aw" id="modal-add-booking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-calendar-plus me-2"></i>Book New Laboratory Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
		  <div class="modal-body">
			<form id="form-add-booking">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Select Patient</label>
							<select class="form-select" name="patient_id" required>
								<option value="">-- Select Patient --</option>
								@foreach($patients as $patient)
									<option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Reference Doctor</label>
							<div class="input-group flex-nowrap">
								<select class="form-select reference-dr-select" name="doctor_name">
									<option value="">-- Select Doctor --</option>
								</select>
								<button type="button" class="btn btn-success btn-add-doctor" title="Add New"><i class="fa fa-plus"></i></button>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label text-primary">Test Name</label>
							<div class="input-group flex-nowrap">
								<select class="form-select book-test-select" name="test_name" id="book-test-name" required>
									<option value="">-- Select Test --</option>
									@foreach($tests as $test)
										<option value="{{ $test->name }}" data-id="{{ $test->id }}" data-price="{{ $test->price }}" data-unit="{{ $test->parameter->unit ?? '' }}" data-bio-ref="{{ $test->parameter->biological_reference ?? '' }}">{{ $test->name }}</option>
									@endforeach
								</select>
								<button type="button" class="btn btn-success btn-add-booking-test" title="Add New Test"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-warning btn-edit-booking-test" title="Edit Selected Test"><i class="fa fa-edit"></i></button>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label text-primary">Price (₹)</label>
							<input type="number" step="0.01" class="form-control" id="book-test-price" name="test_price" placeholder="0.00" required>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label text-danger">Discount (₹)</label>
							<input type="number" step="0.01" class="form-control" id="book-test-discount" name="discount" value="0.00">
						</div>
					</div>
				</div>

				<div class="text-end mb-2 pe-3">
					<span style="font-size:16px; font-weight:700; color:var(--text-dark);">Net Payable: ₹<span id="book-net-payable">0.00</span></span>
					<input type="hidden" name="balance" id="book-hidden-balance">
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Booking Date</label>
							<input type="date" class="form-control" name="appointment_date" value="{{ date('Y-m-d') }}" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Booking Time</label>
							<input type="time" class="form-control" name="appointment_time" value="{{ date('H:i') }}" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Status</label>
							<select class="form-select" name="status">
								<option value="Pending">Pending</option>
								<option value="Completed">Completed</option>
								<option value="Cancelled">Cancelled</option>
							</select>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Notes / Instructions</label>
					<textarea rows="2" class="form-control" name="notes" placeholder="Any special instructions..."></textarea>
				</div>
			</form>
		  </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-save-booking"><i class="fa fa-check"></i> Confirm Booking</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Booking Modal -->
<div class="modal fade modal-aw" id="modal-edit-booking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Laboratory Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
		  <div class="modal-body">
			<form id="form-edit-booking">
				<input type="hidden" name="id" id="edit-booking-id">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Select Patient</label>
							<select class="form-select" name="patient_id" id="edit-patient-id" required>
								<option value="">-- Select Patient --</option>
								@foreach($patients as $patient)
									<option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Reference Doctor</label>
							<div class="input-group flex-nowrap">
								<select class="form-select reference-dr-select" name="doctor_name" id="edit-doctor-name">
									<option value="">-- Select Doctor --</option>
								</select>
								<button type="button" class="btn btn-success btn-add-doctor" title="Add New"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-warning btn-edit-doctor" title="Edit Selected"><i class="fa fa-edit"></i></button>
								<button type="button" class="btn btn-danger btn-delete-doctor" title="Delete Selected"><i class="fa fa-trash"></i></button>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label text-primary">Test Name</label>
							<div class="input-group flex-nowrap">
								<select class="form-select book-test-select" name="test_name" id="edit-test-name" required>
									<option value="">-- Select Test --</option>
									@foreach($tests as $test)
										<option value="{{ $test->name }}" data-id="{{ $test->id }}" data-price="{{ $test->price }}" data-unit="{{ $test->parameter->unit ?? '' }}" data-bio-ref="{{ $test->parameter->biological_reference ?? '' }}">{{ $test->name }}</option>
									@endforeach
								</select>
								<button type="button" class="btn btn-success btn-add-booking-test" title="Add New Test"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-warning btn-edit-booking-test" title="Edit Selected Test"><i class="fa fa-edit"></i></button>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label text-primary">Price (₹)</label>
							<input type="number" step="0.01" class="form-control" id="edit-test-price" name="test_price" placeholder="0.00" required>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label text-danger">Discount (₹)</label>
							<input type="number" step="0.01" class="form-control" id="edit-test-discount" name="discount" value="0.00">
						</div>
					</div>
				</div>

				<div class="text-end mb-2 pe-3">
					<span style="font-size:16px; font-weight:700; color:var(--text-dark);">Net Payable: ₹<span id="edit-net-payable">0.00</span></span>
					<input type="hidden" name="balance" id="edit-hidden-balance">
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Booking Date</label>
							<input type="date" class="form-control" name="appointment_date" id="edit-appointment-date" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Booking Time</label>
							<input type="time" class="form-control" name="appointment_time" id="edit-appointment-time" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Status</label>
							<select class="form-select" name="status" id="edit-status">
								<option value="Pending">Pending</option>
								<option value="Completed">Completed</option>
								<option value="Cancelled">Cancelled</option>
							</select>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Notes / Instructions</label>
					<textarea rows="2" class="form-control" name="notes" id="edit-notes" placeholder="Any special instructions..."></textarea>
				</div>
			</form>
		  </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-update-booking"><i class="fa fa-check"></i> Update Booking</button>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade modal-aw" id="modal-delete-booking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-triangle-exclamation me-2"></i>Delete Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="color:var(--text-muted);">Are you sure you want to delete this booking? This will remove all associated data.</p>
                <input type="hidden" id="delete-booking-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-danger" id="btn-confirm-delete-booking">Delete Permanently</button>
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
				<label class="form-label-aw">Doctor Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control-aw" name="name" required placeholder="e.g. Dr. John Doe">
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Qualification</label>
				<input type="text" class="form-control-aw" name="qualification" placeholder="e.g. MBBS, MD">
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Phone No</label>
				<input type="text" class="form-control-aw" name="phone" placeholder="Phone Number">
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Email</label>
				<input type="email" class="form-control-aw" name="email" placeholder="Email Address" autocomplete="new-password">
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
				<label class="form-label-aw">Doctor Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control-aw" name="name" id="edit-doc-name" required>
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Qualification</label>
				<input type="text" class="form-control-aw" name="qualification" id="edit-doc-qualification">
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Phone No</label>
				<input type="text" class="form-control-aw" name="phone" id="edit-doc-phone">
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Email</label>
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

<!-- Add Booking Test Modal -->
<div class="modal fade modal-aw" id="modal-add-booking-test" tabindex="-1" style="z-index: 1060;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title"><i class="fa fa-flask me-2"></i>Add New Laboratory Test</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body">
		<form id="form-add-booking-test">
			@csrf
			<div class="form-group">
				<label class="form-label-aw">Test/Parameter Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control-aw" name="name" required placeholder="e.g. Glucose Fasting">
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Price (₹) <span class="text-danger">*</span></label>
				<input type="number" step="0.01" class="form-control-aw" name="price" required placeholder="e.g. 250.00">
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Measurement Unit</label>
				<select class="form-select form-control-aw" name="unit">
					<option value="">-- Select Unit --</option>
					@foreach($units as $u)
						<option value="{{ $u->name }}">{{ $u->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Biological Reference</label>
				<input type="text" class="form-control-aw" name="biological_reference" placeholder="e.g. 70 - 110">
			</div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Close</button>
		<button type="button" class="btn-aw-primary" id="btn-save-booking-test"><i class="fa fa-check"></i> Save Test</button>
	  </div>
	</div>
  </div>
</div>

<!-- Edit Booking Test Modal -->
<div class="modal fade modal-aw" id="modal-edit-booking-test" tabindex="-1" style="z-index: 1060;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title"><i class="fa fa-flask me-2"></i>Edit Laboratory Test</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body">
		<form id="form-edit-booking-test">
			@csrf
			<input type="hidden" name="test_id" id="edit-booking-test-id">
			<div class="form-group">
				<label class="form-label-aw">Test/Parameter Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control-aw" name="name" id="edit-booking-test-name" required>
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Price (₹) <span class="text-danger">*</span></label>
				<input type="number" step="0.01" class="form-control-aw" name="price" id="edit-booking-test-price-val" required>
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Measurement Unit</label>
				<select class="form-select form-control-aw" name="unit" id="edit-booking-test-unit">
					<option value="">-- Select Unit --</option>
					@foreach($units as $u)
						<option value="{{ $u->name }}">{{ $u->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group mt-3">
				<label class="form-label-aw">Biological Reference</label>
				<input type="text" class="form-control-aw" name="biological_reference" id="edit-booking-test-bio">
			</div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Close</button>
		<button type="button" class="btn-aw-primary" id="btn-update-booking-test"><i class="fa fa-check"></i> Update Test</button>
	  </div>
	</div>
  </div>
</div>

<!-- JavaScript -->
@push('scripts')
  <script>
	  $(document).ready(function() {
		  $.ajaxSetup({
			  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		  });

          // Select2 initialization function
          function initDynamicSelect2() {
              $('#modal-add-booking select').each(function() {
                  if ($(this).hasClass('select2-hidden-accessible')) {
                      $(this).select2('destroy');
                  }
                  $(this).select2({
                      theme: 'bootstrap-5',
                      width: '100%',
                      dropdownParent: $('#modal-add-booking')
                  });
              });

              $('#modal-edit-booking select').each(function() {
                  if ($(this).hasClass('select2-hidden-accessible')) {
                      $(this).select2('destroy');
                  }
                  $(this).select2({
                      theme: 'bootstrap-5',
                      width: '100%',
                      dropdownParent: $('#modal-edit-booking')
                  });
              });
          }

          $(document).on('shown.bs.modal', '#modal-add-booking, #modal-edit-booking', function() {
              initDynamicSelect2();
          });

          initDynamicSelect2();

          // Fix overlapping modal backdrops z-index
          $(document).on('show.bs.modal', '.modal', function () {
              const zIndex = 1060 + (10 * $('.modal:visible').length);
              $(this).css('z-index', zIndex);
              setTimeout(function() {
                  $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
              }, 0);
          });
          $(document).on('hidden.bs.modal', '.modal', function () {
              if ($('.modal:visible').length > 0) {
                  // Restore modal-open class to body if another modal is still open
                  setTimeout(function() {
                      $('body').addClass('modal-open');
                  }, 0);
              }
          });

          // Fetch Booking Tests Function
          function fetchBookingTests(selectedValue = null) {
              $.get("/api/tests", function(data) {
                  let options = '<option value="">-- Select Test --</option>';
                  data.forEach(function(test) {
                      let unit = test.parameter ? test.parameter.unit : '';
                      let bioRef = test.parameter ? test.parameter.biological_reference : '';
                      options += `<option value="${test.name}" data-id="${test.id}" data-price="${test.price}" data-unit="${unit}" data-bio-ref="${bioRef}">${test.name}</option>`;
                  });
                  $('.book-test-select').each(function() {
                      let currentVal = $(this).val();
                      $(this).html(options);
                      if (selectedValue && $(this).hasClass('active-test-select')) {
                          $(this).val(selectedValue).removeClass('active-test-select').trigger('change');
                      } else {
                          $(this).val(currentVal).trigger('change');
                      }
                  });
              });
          }

          // Test CRUD click handlers
          $(document).on('click', '.btn-add-booking-test', function() {
              $('.book-test-select').removeClass('active-test-select');
              $(this).siblings('.book-test-select').addClass('active-test-select');
              $('#modal-add-booking-test').modal('show');
          });

          $(document).on('click', '.btn-edit-booking-test', function() {
              let select = $(this).siblings('.book-test-select');
              let selectedOption = select.find('option:selected');
              let testId = selectedOption.data('id');
              
              if (!testId) {
                  alert('Please select a valid test to edit.');
                  return;
              }
              
              $('.book-test-select').removeClass('active-test-select');
              select.addClass('active-test-select');

              $('#edit-booking-test-id').val(testId);
              $('#edit-booking-test-name').val(selectedOption.val());
              $('#edit-booking-test-price-val').val(selectedOption.data('price') || 0);
              $('#edit-booking-test-unit').val(selectedOption.data('unit') || '');
              $('#edit-booking-test-bio').val(selectedOption.data('bio-ref') || '');
              $('#modal-edit-booking-test').modal('show');
          });

          $('#btn-save-booking-test').click(function() {
              let btn = $(this);
              if (btn.prop('disabled')) return;
              
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...');
              let formData = $('#form-add-booking-test').serialize();
              
              $.post("{{ route('tests.quick-store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-booking-test').modal('hide');
                  $('#form-add-booking-test')[0].reset();
                  fetchBookingTests(response.test.name);
                  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Save Test');
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to save test.'));
                  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Save Test');
              });
          });

          $('#btn-update-booking-test').click(function() {
              let btn = $(this);
              if (btn.prop('disabled')) return;
              
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');
              let testId = $('#edit-booking-test-id').val();
              let formData = $('#form-edit-booking-test').serialize();
              
              $.ajax({
                  url: "/tests/quick-update/" + testId,
                  type: 'PUT',
                  data: formData,
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-booking-test').modal('hide');
                      fetchBookingTests(response.test.name);
                      btn.prop('disabled', false).html('<i class="fa fa-check"></i> Update Test');
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to update test.'));
                      btn.prop('disabled', false).html('<i class="fa fa-check"></i> Update Test');
                  }
              });
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
				  $('.reference-dr-select').html(options);
				  if (selectedValue) {
					  $('.reference-dr-select').val(selectedValue).trigger('change');
				  } else {
					  $('.reference-dr-select').trigger('change');
				  }
			  });
		  }

		  fetchDoctorSuggestions();

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
			  
			  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');
			  let formData = $('#form-edit-doctor').serialize();
			  let id = $('#edit-doc-id').val();
			  
			  $.ajax({
				  url: "/doctors/" + id,
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

		  // Live Search for Bookings
		  $("#booking-search").on("keyup", function() {
			  var value = $(this).val().toLowerCase();
			  $("#booking-table tbody tr").filter(function() {
				  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			  });
		  });

		  // Auto-fill test price and calculate net
		  function calculateBookingNet() {
			  let price = parseFloat($('#book-test-price').val()) || 0;
			  let discount = parseFloat($('#book-test-discount').val()) || 0;
			  let net = price - discount;
			  if (net < 0) {
				  net = 0;
				  $('#book-test-discount').val(price.toFixed(2));
			  }
			  $('#book-net-payable').text(net.toFixed(2));
			  $('#book-hidden-balance').val(net.toFixed(2));
		  }

		  $('#book-test-name').change(function() {
			  let price = $(this).find(':selected').data('price');
			  $('#book-test-price').val(price ? parseFloat(price).toFixed(2) : '');
			  calculateBookingNet();
		  });

		  $('#book-test-price, #book-test-discount').on('input', function() {
			  calculateBookingNet();
		  });

		  // Save Booking
		  $('#btn-save-booking').click(function() {
			  let btn = $(this);
			  btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...').addClass('disabled');
			  
			  $.post("{{ route('appointments.store') }}", $('#form-add-booking').serialize(), function(response) {
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
					  alert(xhr.responseJSON.message || "Error saving booking. Please check all fields.");
				  }
				  btn.html('<i class="fa fa-check"></i> Confirm Booking').removeClass('disabled');
			  });
		  });

		  // Edit Booking (Populate Modal)
		  $(document).on('click', '.btn-edit', function() {
			  let id = $(this).data('id');
			  $.get("/appointments/" + id, function(data) {
				  $('#edit-booking-id').val(data.id);
				  $('#edit-patient-id').val(data.patient_id).trigger('change');
				  $('#edit-doctor-name').val(data.doctor_name).trigger('change');
				  $('#edit-test-name').val(data.test_name).trigger('change');
				  $('#edit-test-price').val(data.test_price);
				  $('#edit-test-discount').val(data.discount);
				  $('#edit-appointment-date').val(data.appointment_date ? data.appointment_date.split(' ')[0] : '');
				  $('#edit-appointment-time').val(data.appointment_time ? data.appointment_time.substring(0,5) : '');
				  $('#edit-status').val(data.status);
				  $('#edit-notes').val(data.notes);
				  calculateEditNet();
			  });
		  });

		  // Edit Modal Calculation
		  function calculateEditNet() {
			  let price = parseFloat($('#edit-test-price').val()) || 0;
			  let discount = parseFloat($('#edit-test-discount').val()) || 0;
			  let net = price - discount;
			  if (net < 0) {
				  net = 0;
				  $('#edit-test-discount').val(price.toFixed(2));
			  }
			  $('#edit-net-payable').text(net.toFixed(2));
			  $('#edit-hidden-balance').val(net.toFixed(2));
		  }

		  $('#edit-test-name').change(function() {
			  let price = $(this).find(':selected').data('price');
			  $('#edit-test-price').val(price ? parseFloat(price).toFixed(2) : '');
			  calculateEditNet();
		  });

		  $('#edit-test-price, #edit-test-discount').on('input', function() {
			  calculateEditNet();
		  });

		  // Update Booking
		  $('#btn-update-booking').click(function() {
			  let id = $('#edit-booking-id').val();
			  let btn = $(this);
			  btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...').addClass('disabled');
			  
			  $.post("/appointments/update/" + id, $('#form-edit-booking').serialize(), function(response) {
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
					  alert(xhr.responseJSON.message || "Error updating booking.");
				  }
				  btn.html('<i class="fa fa-check"></i> Update Booking').removeClass('disabled');
			  });
		  });

		  // Delete Booking
		  $(document).on('click', '.btn-delete', function() {
			  $('#delete-booking-id').val($(this).data('id'));
		  });

		  $('#btn-confirm-delete-booking').click(function() {
			  let id = $('#delete-booking-id').val();
			  $.ajax({
				  url: "/appointments/" + id,
				  type: 'DELETE',
				  success: function(response) {
					  alert(response.success);
					  location.reload();
				  }
			  });
		  });
	  });
  </script>
@endpush

@endsection



