@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Manage Test Sub-Categories</h4>

				</div>
				<div class="ms-auto">
					<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-subcategory">
						<i class="fa fa-plus-circle me-5"></i> Add New Sub-Category
					</button>
				</div>
			</div>
		</div>
		  
		<section class="content">
			<div class="row">
				<div class="col-12">
					<div class="box">
						<div class="box-body">
							<div class="row mb-3">
								<div class="col-md-4">
									<div class="input-group">
										<span class="input-group-text bg-primary-light border-primary-light"><i class="fa fa-search text-primary"></i></span>
										<input type="text" id="subcategory-search" class="form-control" placeholder="Search sub-categories..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<table class="table border-no" id="subcategories-table">
									<thead>
										<tr>
											<th>ID</th>
											<th>Sub-Category Name</th>
											<th>Main Category</th>
											<th>Description</th>
											<th class="text-end">Action</th>
										</tr>
									</thead>
									<tbody>
										@forelse($subCategories as $sub)
										<tr>
											<td>#{{ $sub->id }}</td>
											<td class="fw-600">{{ $sub->name }}</td>
											<td><span class="badge badge-primary-light">{{ $sub->category->name }}</span></td>
											<td class="text-fade">{{ $sub->description ?? '-' }}</td>
											<td class="text-end">
												<div class="d-flex justify-content-end gap-2">
													<button class="btn btn-primary-light btn-sm btn-edit d-inline-flex align-items-center justify-content-center" 
														data-id="{{ $sub->id }}" 
														data-name="{{ $sub->name }}" 
														data-category_id="{{ $sub->category_id }}"
														data-description="{{ $sub->description }}"
														data-bs-toggle="modal" data-bs-target="#modal-edit-subcategory"
														title="Edit" style="width: 32px; height: 32px;">
														<i class="fa fa-edit"></i>
													</button>
													<button class="btn btn-danger-light btn-sm btn-delete d-inline-flex align-items-center justify-content-center" 
														data-id="{{ $sub->id }}" 
														data-name="{{ $sub->name }}"
														data-bs-toggle="modal" data-bs-target="#modal-delete-subcategory"
														title="Delete" style="width: 32px; height: 32px;">
														<i class="fa fa-trash"></i>
													</button>
												</div>
											</td>
										</tr>
										@empty
										<tr>
											<td colspan="5" class="text-center py-50">
												<i class="fa fa-list-ul fa-3x text-fade d-block mb-10"></i>
												<span class="text-fade fs-18">No sub-categories found.</span>
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

  <!-- Add Modal -->
  <div class="modal center-modal fade" id="modal-add-subcategory" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Add New Sub-Category</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-subcategory">
				<div class="form-group">
					<label class="form-label">Parent Category</label>
					<select class="form-select" name="category_id" required>
						<option value="">Select Category</option>
						@foreach($categories as $cat)
							<option value="{{ $cat->id }}">{{ $cat->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label class="form-label">Sub-Category Name</label>
					<input type="text" class="form-control" name="name" placeholder="e.g. Lipid Profile" required>
				</div>
				<div class="form-group">
					<label class="form-label">Description (Optional)</label>
					<textarea rows="3" class="form-control" name="description" placeholder="Details..."></textarea>
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-save-subcategory">Save Sub-Category</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal center-modal fade" id="modal-edit-subcategory" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Sub-Category</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-subcategory">
				<input type="hidden" id="edit-id">
				<div class="form-group">
					<label class="form-label">Parent Category</label>
					<select class="form-select" id="edit-category_id" name="category_id" required>
						@foreach($categories as $cat)
							<option value="{{ $cat->id }}">{{ $cat->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label class="form-label">Sub-Category Name</label>
					<input type="text" class="form-control" id="edit-name" name="name" required>
				</div>
				<div class="form-group">
					<label class="form-label">Description</label>
					<textarea rows="3" class="form-control" id="edit-description" name="description"></textarea>
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-update-subcategory">Update Changes</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal center-modal fade" id="modal-delete-subcategory" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Delete Sub-Category</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<p>Are you sure you want to remove: <strong id="delete-sub-name" class="text-danger"></strong>?</p>
			<input type="hidden" id="delete-id">
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-danger float-end" id="btn-confirm-delete-subcategory">Delete Permanently</button>
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

		  // Live Search
		  $("#subcategory-search").on("keyup", function() {
			  var value = $(this).val().toLowerCase();
			  $("#subcategories-table tbody tr").filter(function() {
				  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			  });
		  });

		  // Save
		  $('#btn-save-subcategory').click(function() {
              let btn = $(this);
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
              
			  $.post("{{ route('sub-categories.store') }}", $('#form-add-subcategory').serialize(), function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
                  alert(xhr.responseJSON.message || "Error saving sub-category.");
                  btn.prop('disabled', false).text('Save Sub-Category');
              });
		  });

		  // Edit Populate
		  $(document).on('click', '.btn-edit', function() {
			  $('#edit-id').val($(this).data('id'));
			  $('#edit-name').val($(this).data('name'));
			  $('#edit-category_id').val($(this).data('category_id'));
			  $('#edit-description').val($(this).data('description'));
		  });

		  // Update
		  $('#btn-update-subcategory').click(function() {
			  let id = $('#edit-id').val();
              let btn = $(this);
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

			  $.ajax({
                  url: "/sub-categories/" + id,
                  type: 'PUT',
                  data: $('#form-edit-subcategory').serialize(),
                  success: function(response) {
                      alert(response.success);
                      location.reload();
                  },
                  error: function(xhr) {
                      alert(xhr.responseJSON.message || "Error updating sub-category.");
                      btn.prop('disabled', false).text('Update Changes');
                  }
              });
		  });

		  // Delete
		  $(document).on('click', '.btn-delete', function() {
			  $('#delete-id').val($(this).data('id'));
			  $('#delete-sub-name').text($(this).data('name'));
		  });

		  $('#btn-confirm-delete-subcategory').click(function() {
			  let id = $('#delete-id').val();
			  $.ajax({
				  url: "/sub-categories/" + id,
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
