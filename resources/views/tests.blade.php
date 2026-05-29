@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Manage Laboratory Tests</h4>

				</div>
				<div class="ms-auto">
					<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-test">
						<i class="fa fa-plus-circle me-5"></i> Add New Test
					</button>
				</div>
			</div>
		</div>
		  
		<section class="content">
			<div class="row">
				<div class="col-12">
					<div class="box">
                        <style>
                            @media (max-width: 767px) {
                                .card-table thead { display: none; }
                                .card-table tbody tr { display: block; border: 1px solid #eee; margin-bottom: 15px; border-radius: 8px; padding: 10px; background: white !important; }
                                .card-table tbody td { display: flex; justify-content: space-between; align-items: center; border: none !important; padding: 5px 0 !important; text-align: right; }
                                .card-table tbody td::before { content: attr(data-label); font-weight: bold; text-align: left; color: #666; font-size: 12px; }
                                .card-table .text-end { justify-content: flex-end; width: 100%; border-top: 1px solid #f5f5f5 !important; margin-top: 10px; padding-top: 10px !important; }
                            }
                        </style>
						<div class="box-body">
							<div class="row mb-3">
								<div class="col-md-4">
									<div class="input-group">
										<span class="input-group-text bg-primary-light border-primary-light"><i class="fa fa-search text-primary"></i></span>
										<input type="text" id="test-search" class="form-control" placeholder="Search tests..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
									</div>
								</div>
							</div>
							<div class="table-responsive rounded card-table">
								<table class="table border-no table-striped" id="tests-table">
									<thead>
										<tr>
											<th>ID</th>
											<th>Test Name</th>
											<th>Price</th>
											<th>Description</th>
											<th class="text-end">Action</th>
										</tr>
									</thead>
									<tbody>
										@forelse($tests as $test)
										<tr>
											<td class="fw-600 text-primary d-none d-md-table-cell">#{{ $test->id }}</td>
											<td class="fw-600" data-label="Test Name">{{ $test->name }}</td>
											<td class="fw-700 text-primary" data-label="Price">₹{{ number_format($test->price, 2) }}</td>
											<td class="text-fade" data-label="Description">{{ $test->description ?? '-' }}</td>
											<td class="text-end">
												<div class="d-flex justify-content-end gap-2">
													<button class="btn btn-primary-light btn-sm btn-edit d-inline-flex align-items-center justify-content-center" 
														data-id="{{ $test->id }}" 
														data-name="{{ $test->name }}" 
														data-price="{{ $test->price }}"
														data-description="{{ $test->description }}"
														data-bs-toggle="modal" data-bs-target="#modal-edit-test"
														title="Edit Billing" style="width: 32px; height: 32px;">
														<i class="fa fa-edit"></i>
													</button>
                                                    <a href="{{ route('test-parameters.index') }}" class="btn btn-info-light btn-sm d-inline-flex align-items-center justify-content-center" title="Setup Parameters" style="width: 32px; height: 32px;">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
													<button class="btn btn-danger-light btn-sm btn-delete d-inline-flex align-items-center justify-content-center" 
														data-id="{{ $test->id }}" 
														data-name="{{ $test->name }}"
														data-bs-toggle="modal" data-bs-target="#modal-delete-test"
														title="Delete Test" style="width: 32px; height: 32px;">
														<i class="fa fa-trash"></i>
													</button>
												</div>
											</td>
										</tr>
										@empty
										<tr>
											<td colspan="6" class="text-center py-50">
												<i class="fa fa-list-alt fa-3x text-fade d-block mb-10"></i>
												<span class="text-fade fs-18">No laboratory tests found in the database.</span>
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

  <!-- Add Test Modal -->
  <div class="modal center-modal fade" id="modal-add-test" tabindex="-1">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Register New Laboratory Test</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-test">
				<div class="form-group">
					<label class="form-label">Test Name</label>
					<input type="text" class="form-control" name="name" placeholder="e.g. Blood Sugar Level" required>
				</div>
				<div class="form-group">
					<label class="form-label">Price (₹)</label>
					<input type="number" class="form-control" name="price" placeholder="e.g. 500" required>
				</div>


				<div class="form-group">
					<label class="form-label">Description (Optional)</label>
					<textarea rows="2" class="form-control" name="description" placeholder="Short details about the test..."></textarea>
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-save-test">Save Test</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Test Modal -->
  <div class="modal center-modal fade" id="modal-edit-test" tabindex="-1">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Laboratory Test</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-test">
				<input type="hidden" id="edit-id">
				<div class="form-group">
					<label class="form-label">Test Name</label>
					<input type="text" class="form-control" id="edit-name" name="name" required>
				</div>
				<div class="form-group">
					<label class="form-label">Price (₹)</label>
					<input type="number" class="form-control" id="edit-price" name="price" required>
				</div>


				<div class="form-group">
					<label class="form-label">Description</label>
					<textarea rows="2" class="form-control" id="edit-description" name="description"></textarea>
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-update-test">Update Changes</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal center-modal fade" id="modal-delete-test" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Delete Test</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<p>Are you sure you want to remove the test: <strong id="delete-test-name" class="text-danger"></strong>?</p>
			<p class="text-fade fs-12">This action cannot be undone and may affect existing patient records.</p>
			<input type="hidden" id="delete-id">
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-danger float-end" id="btn-confirm-delete">Delete Permanently</button>
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

		  // Live Search for Tests
		  $("#test-search").on("keyup", function() {
			  var value = $(this).val().toLowerCase();
			  $("#tests-table tbody tr").filter(function() {
				  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			  });
		  });

		  // Save Test
		  $('#btn-save-test').click(function() {
			  let formData = $('#form-add-test').serialize();
			  $.post("{{ route('lab-tests.store') }}", formData, function(response) {
				  alert(response.success);
				  location.reload();
			  });
		  });

		  // Edit Test (Populate)
		  $(document).on('click', '.btn-edit', function() {
			  $('#edit-id').val($(this).data('id'));
			  $('#edit-name').val($(this).data('name'));
			  $('#edit-price').val($(this).data('price'));
			  $('#edit-description').val($(this).data('description'));
		  });

		  // Update Test
		  $('#btn-update-test').click(function() {
			  let id = $('#edit-id').val();
			  let btn = $(this);
			  btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...').addClass('disabled');

			  $.post("/lab-tests/update/" + id, $('#form-edit-test').serialize(), function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function() {
				  alert("Failed to update test. Please check inputs.");
				  btn.html('Update Changes').removeClass('disabled');
			  });
		  });

		  // Delete Test
		  $(document).on('click', '.btn-delete', function() {
			  $('#delete-id').val($(this).data('id'));
			  $('#delete-test-name').text($(this).data('name'));
		  });

		  $('#btn-confirm-delete').click(function() {
			  let id = $('#delete-id').val();
			  let btn = $(this);
			  btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Deleting...').addClass('disabled');

			  $.ajax({
				  url: "/lab-tests/" + id,
				  type: 'DELETE',
				  success: function(response) {
					  alert(response.success);
					  location.reload();
				  },
				  error: function() {
					  alert("Failed to delete test.");
					  btn.html('Delete Permanently').removeClass('disabled');
				  }
			  });
		  });
	  });
  </script>

@endsection
