@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Laboratory Bookings</h4>

				</div>
				<div class="ms-auto">
					<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-booking">
						<i class="fa fa-plus-circle me-5"></i> Book New Test
					</button>
				</div>
			</div>
		</div>
		  
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
										<input type="text" id="booking-search" class="form-control" placeholder="Search bookings..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
									</div>
								</div>
							</div>
							<div class="table-responsive rounded card-table" style="max-height: 500px; overflow-y: auto;">
								<table class="table border-no" id="booking-table">
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
											<td class="d-none d-md-table-cell text-fade">#B-{{ $booking->id }}</td>
											<td class="fw-600">{{ $booking->patient->first_name }} {{ $booking->patient->last_name }}</td>
											<td class="text-fade d-none d-lg-table-cell">{{ $booking->test_name }}</td>
											<td>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('d M Y') }}</td>
											<td class="fw-700 text-primary d-none d-sm-table-cell">₹{{ number_format($booking->test_price, 2) }}</td>
											<td><span class="badge badge-{{ $booking->status == 'Completed' ? 'success' : ($booking->status == 'Cancelled' ? 'danger' : 'warning') }}">{{ $booking->status }}</span></td>
											<td class="text-end">
												<div class="d-flex justify-content-end gap-2">
													<button class="btn btn-primary-light btn-sm btn-edit d-flex align-items-center justify-content-center" data-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#modal-edit-booking" title="Edit Booking" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-edit"></i></button>
													<button class="btn btn-danger-light btn-sm btn-delete d-flex align-items-center justify-content-center" data-id="{{ $booking->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete-booking" title="Delete" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-trash"></i></button>
												</div>
											</td>
										</tr>
										@empty
										<tr>
											<td colspan="7" class="text-center py-50">
												<i class="fa fa-folder-open-o fa-3x text-fade d-block mb-10"></i>
												<span class="text-fade fs-18">No laboratory bookings found in the database.</span>
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

  <!-- Add Booking Modal -->
  <div class="modal center-modal fade" id="modal-add-booking" tabindex="-1">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Book New Laboratory Test</h5>
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
							<select class="form-select" name="test_name" id="book-test-name" required>
								<option value="">-- Select Test --</option>
								@foreach($tests as $test)
									<option value="{{ $test->name }}" data-price="{{ $test->price }}">{{ $test->name }}</option>
								@endforeach
							</select>
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

				<div class="row mb-15">
					<div class="col-12">
						<div class="bg-primary-light p-3 rounded-10 text-end">
							<h5 class="mb-0 fw-bold">Net Payable: ₹<span id="book-net-payable">0.00</span></h5>
							<input type="hidden" name="balance" id="book-hidden-balance">
						</div>
					</div>
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
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-save-booking">Confirm Booking</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Booking Modal -->
  <div class="modal center-modal fade" id="modal-edit-booking" tabindex="-1">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Laboratory Booking</h5>
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
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label text-primary">Test Name</label>
							<select class="form-select" name="test_name" id="edit-test-name" required>
								<option value="">-- Select Test --</option>
								@foreach($tests as $test)
									<option value="{{ $test->name }}" data-price="{{ $test->price }}">{{ $test->name }}</option>
								@endforeach
							</select>
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

				<div class="row mb-15">
					<div class="col-12">
						<div class="bg-primary-light p-3 rounded-10 text-end">
							<h5 class="mb-0 fw-bold">Net Payable: ₹<span id="edit-net-payable">0.00</span></h5>
							<input type="hidden" name="balance" id="edit-hidden-balance">
						</div>
					</div>
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
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-update-booking">Update Booking</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- View Booking Modal -->
  <div class="modal center-modal fade" id="modal-view-booking" tabindex="-1">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header bg-primary">
			<h5 class="modal-title text-white">Booking Details</h5>
			<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body p-0" id="view-booking-content">
			<!-- Populated via JS -->
			<div class="text-center py-50">
				<div class="spinner-border text-primary" role="status">
					<span class="visually-hidden">Loading...</span>
				</div>
				<p class="mt-10 text-fade">Fetching booking details...</p>
			</div>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-success float-end" onclick="window.print()"><i class="fa fa-print me-1"></i> Print Receipt</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal center-modal fade" id="modal-delete-booking" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Delete Booking</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<p>Are you sure you want to delete this booking? This will remove all associated data.</p>
			<input type="hidden" id="delete-booking-id">
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-danger float-end" id="btn-confirm-delete-booking">Delete Permanently</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
	  $(document).ready(function() {
		  $.ajaxSetup({
			  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		  });

		  // Fetch Doctor Suggestions
		  function fetchDoctorSuggestions(selectedValue = null) {
			  $.get("{{ route('doctors.suggestions') }}", function(data) {
				  let options = '<option value="">-- Select Doctor --</option><option value="Self">Self</option>';
				  data.forEach(function(doctor) {
					  options += '<option value="' + doctor.name + '">' + doctor.name + '</option>';
				  });
				  $('.reference-dr-select').html(options);
                  if (selectedValue) {
                      $('.reference-dr-select').val(selectedValue);
                  }
			  });
		  }

          $(document).on('click', '.btn-add-doctor', function() {
              window.open("{{ route('patients') }}", '_blank');
              alert('Redirecting to Patients page to manage Doctors. Please refresh this page after adding.');
          });

          fetchDoctorSuggestions();

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
				  alert(xhr.responseJSON.message || "Error saving booking. Please check all fields.");
				  btn.html('Save Booking').removeClass('disabled');
			  });
		  });

		  // Edit Booking (Populate Modal)
		  $(document).on('click', '.btn-edit', function() {
			  let id = $(this).data('id');
			  $.get("/appointments/" + id, function(data) {
				  $('#edit-booking-id').val(data.id);
				  $('#edit-patient-id').val(data.patient_id);
				  $('#edit-doctor-name').val(data.doctor_name);
				  $('#edit-test-name').val(data.test_name);
				  $('#edit-test-price').val(data.test_price);
				  $('#edit-test-discount').val(data.discount);
				  $('#edit-appointment-date').val(data.appointment_date);
				  $('#edit-appointment-time').val(data.appointment_time);
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
				  alert(xhr.responseJSON.message || "Error updating booking.");
				  btn.html('Update Booking').removeClass('disabled');
			  });
		  });

		  // View Booking
		  $(document).on('click', '.btn-view', function() {
			  let id = $(this).data('id');
			  $('#view-booking-content').html(`
				<div class="text-center py-50">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
					<p class="mt-10 text-fade">Fetching booking details...</p>
				</div>
			  `);
			  
			  $.get("/appointments/" + id, function(data) {
				  let dateStr = new Date(data.appointment_date).toLocaleDateString('en-GB', {
					  day: '2-digit', month: 'short', year: 'numeric'
				  });
				  
				  let content = `
				  	<div class="p-20">
						<div class="row mb-15">
							<div class="col-md-6">
								<h4 class="text-primary mb-0">#B-${data.id}</h4>
								<span class="badge badge-${data.status == 'Completed' ? 'success' : (data.status == 'Cancelled' ? 'danger' : 'warning')}">${data.status}</span>
							</div>
							<div class="col-md-6 text-end">
								<p class="mb-0"><strong>Booking Date:</strong> ${dateStr}</p>
								<p class="mb-0"><strong>Time:</strong> ${data.appointment_time}</p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-6">
								<h5 class="fw-600 mb-5">Patient Information</h5>
								<p class="mb-5"><strong>Name:</strong> ${(data.patient || {}).first_name || ''} ${(data.patient || {}).last_name || ''}</p>
								<p class="mb-5"><strong>Patient ID:</strong> ${(data.patient || {}).patient_id || ''}</p>
								<p class="mb-5"><strong>Phone:</strong> ${(data.patient || {}).phone || 'N/A'}</p>
							</div>
							<div class="col-md-6">
								<h5 class="fw-600 mb-5">Reference Details</h5>
								<p class="mb-5"><strong>Doctor:</strong> ${data.doctor_name || 'Self'}</p>
								<p class="mb-5"><strong>Test Name:</strong> ${data.test_name}</p>
							</div>
						</div>
						<div class="mt-20 bg-light p-15 rounded">
							<h5 class="fw-600 mb-10">Financial Summary</h5>
							<div class="d-flex justify-content-between mb-5">
								<span>Test Price:</span>
								<span class="fw-600">₹${parseFloat(data.test_price || 0).toFixed(2)}</span>
							</div>
							<div class="d-flex justify-content-between mb-5 text-danger">
								<span>Discount:</span>
								<span>- ₹${parseFloat(data.discount || 0).toFixed(2)}</span>
							</div>
							<hr>
							<div class="d-flex justify-content-between align-items-center">
								<h4 class="mb-0 fw-700">Net Payable:</h4>
								<h4 class="mb-0 text-primary fw-700">₹${parseFloat(data.balance || 0).toFixed(2)}</h4>
							</div>
						</div>
						${data.notes ? `
							<div class="mt-20">
								<h5 class="fw-600 mb-5 text-fade">Notes / Instructions</h5>
								<div class="alert alert-warning-light mb-0">
									${data.notes}
								</div>
							</div>
						` : ''}
					</div>
				  `;
				  $('#view-booking-content').html(content);
			  }).fail(function() {
				  $('#view-booking-content').html(`
					<div class="text-center py-50 text-danger">
						<i class="fa fa-exclamation-triangle fa-3x mb-10"></i>
						<p>Failed to load booking details. Please try again.</p>
					</div>
				  `);
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

@endsection
