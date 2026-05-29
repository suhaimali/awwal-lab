@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Patients</h4>

				</div>
				<div class="ms-auto">
					<button type="button" class="btn btn-primary btn-sm btn-md-lg" data-bs-toggle="modal" data-bs-target="#modal-add-patient">
						<i class="fa fa-plus-circle me-5"></i> <span class="d-none d-md-inline">Add New Patient</span>
					</button>
				</div>
			</div>
		</div>
		  
		<!-- Main content -->
		<section class="content">
			<style>
				@media (max-width: 767px) {
					.card-table th, .card-table td {
						padding: 10px 8px !important;
						font-size: 12px;
					}
					.page-title { font-size: 18px; }
				}
			</style>
			<div class="row">
				<div class="col-12">
					<div class="box">
						<div class="box-body">
							<div class="row mb-3">
								<div class="col-md-4">
									<div class="input-group">
										<span class="input-group-text bg-primary-light border-primary-light"><i class="fa fa-search text-primary"></i></span>
										<input type="text" id="patient-search" class="form-control" placeholder="Search by name, ID, or phone..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
									</div>
								</div>
							</div>
							<div class="table-responsive rounded card-table" style="max-height: 500px; overflow-y: auto;">
								<table class="table border-no" id="patient-table" style="width: 100%;">
									<thead>
										<tr>
											<th class="d-none d-md-table-cell">ID</th>
											<th>Name</th>
											<th class="d-none d-lg-table-cell">Gender</th>
											<th class="d-none d-md-table-cell">Age</th>
											<th>Phone</th>
											<th class="d-none d-xl-table-cell">Email</th>
											<th class="d-none d-lg-table-cell text-primary">Amount</th>
											<th class="d-none d-xl-table-cell text-danger">Discount</th>
											<th class="d-none d-sm-table-cell">Balance</th>
											<th class="d-none d-lg-table-cell">Status</th>
											<th class="text-end">Action</th>
										</tr>
									</thead>
									<tbody>
										@forelse($patients as $patient)
                                        @php
                                            $latestApp = $patient->appointments->last();
                                            $totalPrice = $latestApp->test_price ?? 0;
                                            $totalDiscount = $latestApp->discount ?? 0;
                                            $netBalance = $latestApp->balance ?? ($totalPrice - $totalDiscount);
                                        @endphp
										<tr>
											<td class="fw-600 text-primary d-none d-md-table-cell">{{ str_replace(['#P-', '#'], '', $patient->patient_id) }}</td>
											<td class="fw-600">{{ $patient->first_name }} {{ $patient->last_name }}</td>
											<td class="d-none d-lg-table-cell">{{ $patient->gender }}</td>
											<td class="d-none d-md-table-cell">{{ $patient->age }}</td>
											<td>{{ $patient->phone }}</td>
											<td class="d-none d-xl-table-cell text-fade fs-12">{{ $patient->email }}</td>
											<td class="fw-bold d-none d-lg-table-cell">₹{{ number_format($totalPrice, 2) }}</td>
											<td class="text-danger d-none d-xl-table-cell">₹{{ number_format($totalDiscount, 2) }}</td>
											<td class="d-none d-sm-table-cell fw-bold text-success">₹{{ number_format($netBalance, 2) }}</td>
											<td class="d-none d-lg-table-cell"><span class="badge badge-{{ $patient->status == 'Active' ? 'success' : 'danger' }} p-1 fs-10">{{ $patient->status }}</span></td>
											<td class="text-end">												
												<div class="d-flex justify-content-end gap-2">
													<button class="btn btn-info-light btn-sm btn-view d-flex align-items-center justify-content-center" data-id="{{ $patient->id }}" data-bs-toggle="modal" data-bs-target="#modal-view-patient" title="View Details" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-eye"></i></button>
													<button class="btn btn-success-light btn-sm btn-invoice d-none d-lg-flex align-items-center justify-content-center" data-id="{{ $patient->id }}" title="Generate Invoice" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-file-pdf-o"></i></button>
													<button class="btn btn-warning-light btn-sm btn-edit d-flex align-items-center justify-content-center" data-id="{{ $patient->id }}" data-bs-toggle="modal" data-bs-target="#modal-edit-patient" title="Edit Patient" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-edit"></i></button>
													<button class="btn btn-danger-light btn-sm btn-delete d-flex align-items-center justify-content-center" data-id="{{ $patient->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete-patient" title="Delete" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-trash"></i></button>
												</div>
											</td>
										</tr>
										@empty
										<tr>
											<td colspan="11" class="text-center py-50">
												<i class="fa fa-user-times fa-3x text-fade d-block mb-10"></i>
												<span class="text-fade fs-18">No patients found in the database.</span>
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
		<!-- /.content -->
	  </div>
  </div>
  <!-- Add Patient Modal -->
  <div class="modal center-modal fade" id="modal-add-patient" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Add New Patient</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-patient">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Patient ID</label>
							<input type="text" class="form-control" name="patient_id" placeholder="Auto-generated if blank" autocomplete="new-password">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Phone No</label>
							<input type="text" class="form-control" name="phone" placeholder="Phone Number">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">First Name</label>
							<input type="text" class="form-control" name="first_name" placeholder="First Name">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Last Name</label>
							<input type="text" class="form-control" name="last_name" placeholder="Last Name">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Gender</label>
							<select class="form-select" name="gender">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
								<option value="Other">Other</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Age</label>
							<input type="number" class="form-control" name="age" placeholder="Age">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Email</label>
							<input type="email" class="form-control" name="email" placeholder="Email" autocomplete="new-password">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Status</label>
							<select class="form-select" name="status">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label">Reference Dr.</label>
							<div class="input-group flex-nowrap">
								<select class="form-select reference-dr-select" name="reference_dr">
									<option value="">-- Select Doctor --</option>
								</select>
								<button type="button" class="btn btn-success btn-add-doctor" title="Add New"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-warning btn-edit-doctor" title="Edit Selected"><i class="fa fa-edit"></i></button>
								<button type="button" class="btn btn-danger btn-delete-doctor" title="Delete Selected"><i class="fa fa-trash"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Address</label>
					<textarea rows="2" class="form-control" name="address" placeholder="Address"></textarea>
				</div>

			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-save-patient">Save Patient</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- View Modal -->
  <div class="modal center-modal fade" id="modal-view-patient" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Patient Details</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<div class="row">
				<div class="col-md-6">
					<p><strong>Patient ID:</strong> <span id="view-patient-id"></span></p>
				</div>
				<div class="col-md-6">
					<p><strong>Phone:</strong> <span id="view-phone"></span></p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<p><strong>First Name:</strong> <span id="view-first-name"></span></p>
				</div>
				<div class="col-md-6">
					<p><strong>Last Name:</strong> <span id="view-last-name"></span></p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<p><strong>Gender:</strong> <span id="view-gender"></span></p>
				</div>
				<div class="col-md-6">
					<p><strong>Age:</strong> <span id="view-age"></span></p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<p><strong>Email:</strong> <span id="view-email"></span></p>
				</div>
				<div class="col-md-4">
					<p><strong>Reference Dr:</strong> <span id="view-reference-dr"></span></p>
				</div>
				<div class="col-md-4">
					<p><strong>Status:</strong> <span id="view-status"></span></p>
				</div>
			</div>
			<p><strong>Address:</strong> <span id="view-address"></span></p>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal center-modal fade" id="modal-edit-patient" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Patient</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-patient">
				<input type="hidden" id="edit-id" name="id">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label text-primary">Patient ID</label>
							<input type="text" class="form-control" id="edit-patient-id" name="patient_id" placeholder="Patient ID" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Phone No</label>
							<input type="text" class="form-control" id="edit-phone" name="phone" placeholder="Phone No">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">First Name</label>
							<input type="text" class="form-control" id="edit-first-name" name="first_name" placeholder="First Name">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Last Name</label>
							<input type="text" class="form-control" id="edit-last-name" name="last_name" placeholder="Last Name">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Gender</label>
							<select class="form-select" id="edit-gender" name="gender">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
								<option value="Other">Other</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Age</label>
							<input type="number" class="form-control" id="edit-age" name="age" placeholder="Age">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Email</label>
							<input type="email" class="form-control" id="edit-email" name="email" placeholder="Email" autocomplete="new-password">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Status</label>
							<select class="form-select" id="edit-status" name="status">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label">Reference Dr.</label>
							<div class="input-group flex-nowrap">
								<select class="form-select reference-dr-select" id="edit-reference-dr" name="reference_dr">
									<option value="">-- Select Doctor --</option>
								</select>
								<button type="button" class="btn btn-success btn-add-doctor" title="Add New"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-warning btn-edit-doctor" title="Edit Selected"><i class="fa fa-edit"></i></button>
								<button type="button" class="btn btn-danger btn-delete-doctor" title="Delete Selected"><i class="fa fa-trash"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Address</label>
					<textarea rows="2" class="form-control" id="edit-address" name="address" placeholder="Address"></textarea>
				</div>

			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-update-patient">Update Patient</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal center-modal fade" id="modal-delete-patient" tabindex="-1">
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
			<input type="hidden" id="delete-id">
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-danger float-end" id="btn-confirm-delete">Delete Permanently</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Quick Book Test Modal -->
  <div class="modal center-modal fade" id="modal-patient-book-test" tabindex="-1">
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
					<label class="form-label">Test Name</label>
					<select class="form-select" name="test_name" id="quick-book-test-name" required>
						<option value="">-- Select Test --</option>
						@foreach($labTests as $test)
							<option value="{{ $test->name }}" data-price="{{ $test->price }}">{{ $test->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label class="form-label">Price (₹)</label>
							<input type="number" step="0.01" class="form-control" name="test_price" id="quick-book-price" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label class="form-label text-danger">Discount (₹)</label>
							<input type="number" step="0.01" class="form-control" name="test_discount" id="quick-book-discount" value="0.00">
						</div>
					</div>
				</div>
				<div class="bg-primary-light p-3 rounded mb-15 text-end">
					<h5 class="mb-0 fw-bold">Net Payable: ₹<span id="quick-book-net-payable">0.00</span></h5>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label class="form-label">Date</label>
							<input type="date" class="form-control" name="appointment_date" value="{{ date('Y-m-d') }}" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label class="form-label">Status</label>
							<select class="form-select" name="status">
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
  <div class="modal center-modal fade" id="modal-add-doctor" tabindex="-1" style="z-index: 1060;">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Add New Doctor</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-doctor">
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
			<button type="button" class="btn btn-primary float-end" id="btn-save-doctor">Save Doctor</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Doctor Modal -->
  <div class="modal center-modal fade" id="modal-edit-doctor" tabindex="-1" style="z-index: 1060;">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Doctor</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-doctor">
				<input type="hidden" name="doctor_id" id="edit-doc-id">
				<div class="form-group">
					<label class="form-label">Doctor Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="name" id="edit-doc-name" required>
				</div>
				<div class="form-group">
					<label class="form-label">Qualification</label>
					<input type="text" class="form-control" name="qualification" id="edit-doc-qualification">
				</div>
				<div class="form-group">
					<label class="form-label">Phone No</label>
					<input type="text" class="form-control" name="phone" id="edit-doc-phone">
				</div>
				<div class="form-group">
					<label class="form-label">Email</label>
					<input type="email" class="form-control" name="email" id="edit-doc-email" autocomplete="new-password">
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-update-doctor">Update Doctor</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

		  // Live Search for Patients
		  $("#patient-search").on("keyup", function() {
			  var value = $(this).val().toLowerCase();
			  $("#patient-table tbody tr").filter(function() {
				  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			  });
		  });

		  // Generate Invoice
		  $(document).on('click', '.btn-invoice', function(e) {
			  e.preventDefault();
			  let id = $(this).data('id');
			  let btn = $(this);
			  let originalHtml = btn.html();
			  btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Loading...').addClass('disabled');

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
					  doc.text("SUHAIM SOFT LAB MS", 20, 20);
					  
					  doc.setFontSize(10);
					  doc.setFont("helvetica", "normal");
					  doc.setTextColor(0); // Black
					  doc.text("Professional Laboratory Management System", 20, 27);
					  doc.text("Website: suhaimsoft.netlify.app", 20, 32);
					  
					  doc.setDrawColor(0);
					  doc.setLineWidth(0.5);
					  doc.line(20, 40, 190, 40);

					  // --- Invoice Info (Moved further right to avoid overlap) ---
					  doc.setFontSize(10);
                      doc.setTextColor(0);
					  doc.setFont("helvetica", "bold");
					  doc.text("INVOICE DETAILS", 150, 50);
					  doc.setFont("helvetica", "normal");
					  const now = new Date();
					  const dateStr = now.toLocaleDateString();
					  const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
					  doc.text(`Date: ${dateStr} ${timeStr}`, 150, 56);
					  doc.text(`Invoice No: INV-${patient.id}${Math.floor(Math.random() * 1000)}`, 150, 62);

					  // --- Patient Info ---
					  doc.setFont("helvetica", "bold");
					  doc.text("PATIENT INFORMATION:", 20, 50);
					  
					  doc.setFontSize(9);
					  // Column 1
					  doc.setFont("helvetica", "bold"); doc.text("Name:", 20, 58);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.first_name} ${patient.last_name}`, 45, 58);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Patient ID:", 20, 63);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.patient_id.replace('#P-', '').replace('#', '')}`, 45, 63);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Gender:", 20, 68);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.gender}`, 45, 68);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Age:", 20, 73);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.age}`, 45, 73);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Phone:", 20, 78);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.phone || 'N/A'}`, 45, 78);

					  // Column 2
					  doc.setFont("helvetica", "bold"); doc.text("Ref. Dr:", 95, 58);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.reference_dr || 'Self'}`, 115, 58);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Address:", 95, 63);
					  doc.setFont("helvetica", "normal"); doc.text(`${patient.address || 'N/A'}`, 115, 63);
					  
					  doc.setFont("helvetica", "bold"); doc.text("Time:", 95, 68);
					  doc.setFont("helvetica", "normal"); doc.text(`${now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })}`, 115, 68);

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
						  startY: 90,
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
				  $('.reference-dr-select').html(options);
                  if (selectedValue) {
                      $('.reference-dr-select').val(selectedValue);
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
              let formData = $('#form-add-doctor').serialize();
              $.post("{{ route('doctors.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-doctor').modal('hide');
                  $('#form-add-doctor')[0].reset();
                  fetchDoctorSuggestions(response.doctor.name);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save doctor.'));
              });
          });

          $('#btn-update-doctor').click(function() {
              let docId = $('#edit-doc-id').val();
              let formData = $('#form-edit-doctor').serialize();
              $.ajax({
                  url: "/doctors/" + docId,
                  type: 'PUT',
                  data: formData,
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-doctor').modal('hide');
                      fetchDoctorSuggestions(response.doctor.name);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update doctor.'));
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

		  // View Patient
		  $(document).on('click', '.btn-view', function() {
			  let id = $(this).data('id');
			  $.get("/patients/" + id, function(data) {
				  $('#view-patient-id').text(data.patient_id);
				  $('#view-first-name').text(data.first_name);
				  $('#view-last-name').text(data.last_name);
				  $('#view-gender').text(data.gender);
				  $('#view-age').text(data.age);
				  $('#view-phone').text(data.phone);
				  $('#view-email').text(data.email);
				  $('#view-reference-dr').text(data.reference_dr);
				  $('#view-status').text(data.status);
				  $('#view-address').text(data.address);
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
				  $('#edit-phone').val(data.phone);
				  $('#edit-email').val(data.email);
				  $('#edit-reference-dr').val(data.reference_dr);
				  $('#edit-status').val(data.status);
				  $('#edit-address').val(data.address);

                  // Populate existing appointments
                  let testRowsHtml = '';
                  if (data.appointments && data.appointments.length > 0) {
                      data.appointments.forEach(function(app, index) {
                          testRowsHtml += `
                            <div class="row test-row mb-2">
                                <input type="hidden" name="appointment_id[]" value="${app.id}">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="form-select edit-patient-test-name" name="test_name[]">
                                            <option value="">-- Select Test --</option>
                                            @foreach($labTests as $test)
                                                <option value="{{ $test->name }}" data-price="{{ $test->price }}" ${app.test_name == '{{ $test->name }}' ? 'selected' : ''}>{{ $test->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" class="form-control edit-patient-test-price" name="test_price[]" value="${parseFloat(app.test_price).toFixed(2)}">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" class="form-control edit-patient-test-discount" name="test_discount[]" value="${parseFloat(app.discount).toFixed(2)}">
                                </div>
                                <div class="col-md-1 d-flex align-items-end mb-3">
                                    ${index === 0 ? '<button type="button" class="btn btn-success btn-sm btn-edit-add-test-row"><i class="fa fa-plus"></i></button>' : '<button type="button" class="btn btn-danger btn-sm btn-remove-test-row"><i class="fa fa-trash"></i></button>'}
                                </div>
                            </div>`;
                      });
                  } else {
                      // Default empty row if no appointments found
                      testRowsHtml = `
                        <div class="row test-row mb-2">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <select class="form-select edit-patient-test-name" name="test_name[]">
                                        <option value="" selected>-- Select Test (Optional) --</option>
                                        @foreach($labTests as $test)
                                            <option value="{{ $test->name }}" data-price="{{ $test->price }}">{{ $test->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" class="form-control edit-patient-test-price" name="test_price[]" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" class="form-control edit-patient-test-discount" name="test_discount[]" placeholder="0.00" value="0.00">
                            </div>
                            <div class="col-md-1 d-flex align-items-end mb-3">
                                <button type="button" class="btn btn-success btn-sm btn-edit-add-test-row"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>`;
                  }
                  $('#edit-patient-tests-container').html(testRowsHtml);
                  calculateNetPayable('edit');
			  });
		  });

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
	  });
  </script>

@endsection
