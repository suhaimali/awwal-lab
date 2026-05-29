@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Manage Test Categories</h4>

				</div>
				<div class="ms-auto">
					<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-category">
						<i class="fa fa-plus-circle me-5"></i> Add New Category
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
										<input type="text" id="category-search" class="form-control" placeholder="Search categories..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<table class="table border-no" id="categories-table">
									<thead>
										<tr>
											<th>ID</th>
											<th>Category Name</th>
											<th>Description</th>
											<th>Created Date</th>
											<th class="text-end">Action</th>
										</tr>
									</thead>
									<tbody>
										@forelse($categories as $category)
										<tr>
											<td>#{{ $category->id }}</td>
											<td class="fw-600">{{ $category->name }}</td>
											<td class="text-fade">{{ $category->description ?? '-' }}</td>
											<td>{{ $category->created_at->format('d M Y') }}</td>
											<td class="text-end">
												<div class="d-flex justify-content-end gap-2">
													<button class="btn btn-primary-light btn-sm btn-edit d-inline-flex align-items-center justify-content-center" 
														data-id="{{ $category->id }}" 
														data-name="{{ $category->name }}" 
														data-description="{{ $category->description }}"
														data-bs-toggle="modal" data-bs-target="#modal-edit-category"
														title="Edit Category" style="width: 32px; height: 32px;">
														<i class="fa fa-edit"></i>
													</button>
													<button class="btn btn-danger-light btn-sm btn-delete d-inline-flex align-items-center justify-content-center" 
														data-id="{{ $category->id }}" 
														data-name="{{ $category->name }}"
														data-bs-toggle="modal" data-bs-target="#modal-delete-category"
														title="Delete Category" style="width: 32px; height: 32px;">
														<i class="fa fa-trash"></i>
													</button>
												</div>
											</td>
										</tr>
										@empty
										<tr>
											<td colspan="5" class="text-center py-50">
												<i class="fa fa-tags fa-3x text-fade d-block mb-10"></i>
												<span class="text-fade fs-18">No categories found in the database.</span>
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

  <!-- Add Category Modal -->
  <div class="modal center-modal fade" id="modal-add-category" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Add New Category</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-add-category">
				<div class="form-group">
					<label class="form-label">Category Name</label>
					<input type="text" class="form-control" name="name" placeholder="e.g. Hematology" required>
				</div>
				<div class="form-group">
					<label class="form-label">Description (Optional)</label>
					<textarea rows="3" class="form-control" name="description" placeholder="Details about this category..."></textarea>
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-save-category">Save Category</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Edit Category Modal -->
  <div class="modal center-modal fade" id="modal-edit-category" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Category</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-category">
				<input type="hidden" id="edit-id">
				<div class="form-group">
					<label class="form-label">Category Name</label>
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
			<button type="button" class="btn btn-primary float-end" id="btn-update-category">Update Changes</button>
		  </div>
		</div>
	  </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal center-modal fade" id="modal-delete-category" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Delete Category</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<p>Are you sure you want to remove the category: <strong id="delete-category-name" class="text-danger"></strong>?</p>
			<p class="text-fade fs-12">Deleting a category may affect reports using it.</p>
			<input type="hidden" id="delete-id">
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-danger float-end" id="btn-confirm-delete-category">Delete Permanently</button>
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
		  $("#category-search").on("keyup", function() {
			  var value = $(this).val().toLowerCase();
			  $("#categories-table tbody tr").filter(function() {
				  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			  });
		  });

		  // Save Category
		  $('#btn-save-category').click(function() {
			  let formData = $('#form-add-category').serialize();
			  $.post("{{ route('categories.store') }}", formData, function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(response) {
                  alert(response.responseJSON.message || "Error saving category.");
              });
		  });

		  // Edit Category (Populate)
		  $(document).on('click', '.btn-edit', function() {
			  $('#edit-id').val($(this).data('id'));
			  $('#edit-name').val($(this).data('name'));
			  $('#edit-description').val($(this).data('description'));
		  });

		  // Update Category
		  $('#btn-update-category').click(function() {
			  let id = $('#edit-id').val();
			  $.ajax({
                  url: "/categories/" + id,
                  type: 'PUT',
                  data: $('#form-edit-category').serialize(),
                  success: function(response) {
                      alert(response.success);
                      location.reload();
                  },
                  error: function() {
                      alert("Failed to update category.");
                  }
              });
		  });

		  // Delete Category
		  $(document).on('click', '.btn-delete', function() {
			  $('#delete-id').val($(this).data('id'));
			  $('#delete-category-name').text($(this).data('name'));
		  });

		  $('#btn-confirm-delete-category').click(function() {
			  let id = $('#delete-id').val();
			  $.ajax({
				  url: "/categories/" + id,
				  type: 'DELETE',
				  success: function(response) {
					  alert(response.success);
					  location.reload();
				  },
				  error: function() {
					  alert("Failed to delete category.");
				  }
			  });
		  });
	  });
  </script>
@endsection
