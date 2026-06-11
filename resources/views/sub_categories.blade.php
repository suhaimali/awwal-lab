@extends('layouts.app')
@section('title', ' | Sub-Categories')
@section('page-title', 'Sub-Categories')
@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-list-ul"></i></div>
        <div>
            <div>Sub-Categories</div>
            <div style="font-size:13px;font-weight:400;color:var(--text-muted);margin-top:2px;">Manage sub-category groupings</div>
        </div>
    </div>
    <button type="button" class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-subcategory">
        <i class="fa fa-plus"></i> Add New Sub-Category
    </button>
</div>
<style>


    .cat-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
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

    .btn-icon-circle.delete:hover {
        color: #ef4444;
        border-color: #fecaca;
        background: #fef2f2;
    }

    .cat-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        color: #1e293b;
        font-size: 15px;
    }


</style>

<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-list" style="color:var(--primary);"></i> All Sub-Categories</div>
        <div style="position:relative;">
            <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
            <input type="text" id="subcategory-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search sub-categories..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="name_1149">
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">
            
                <table class="table-modern" id="subcategories-table">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Sub-Category Name</th>
                            <th>Main Category</th>
                            <th>Description</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subCategories as $sub)
                        <tr>
                            <td data-label="SL No">
                                <span class="badge-aw" style="background:#f1f5f9;color:#475569;font-family:monospace;font-size:12px;padding:6px 10px;border-radius:6px;border:1px solid #e2e8f0;">#{{ str_pad($sub->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td data-label="Sub-Category Name">
                                <div class="cat-name-cell">
                                    <div class="cat-icon-box" style="background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); color: #a855f7; box-shadow: 0 4px 10px rgba(168, 85, 247, 0.15);">
                                        <i class="fa fa-list-ul"></i>
                                    </div>
                                    <span>{{ $sub->name }}</span>
                                </div>
                            </td>
                            <td data-label="Main Category">
                                <span class="badge-aw" style="background:#eff6ff;color:#3b82f6;border:1px solid #bfdbfe;font-weight:600;padding:6px 12px;border-radius:8px;"><i class="fa fa-tag me-1"></i>{{ $sub->category->name }}</span>
                            </td>
                            <td data-label="Description" style="color:#64748b;font-size:13px;line-height:1.5;">{{ Str::limit($sub->description, 50, '...') ?? '-' }}</td>
                            <td data-label="Action" class="text-end">
                                <div class="action-btn-group">
                                    <button class="btn-icon-circle edit btn-edit"
                                        data-id="{{ $sub->id }}"
                                        data-name="{{ $sub->name }}"
                                        data-category_id="{{ $sub->category_id }}"
                                        data-description="{{ $sub->description }}"
                                        data-bs-toggle="modal" data-bs-target="#modal-edit-subcategory"
                                        title="Edit"><i class="fa fa-pen"></i></button>
                                    <button class="btn-icon-circle delete btn-delete"
                                        data-id="{{ $sub->id }}"
                                        data-name="{{ $sub->name }}"
                                        data-bs-toggle="modal" data-bs-target="#modal-delete-subcategory"
                                        title="Delete"><i class="fa fa-trash"></i></button>
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

<!-- Add Sub-Category Modal -->
<div class="modal fade modal-aw" id="modal-add-subcategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-list-ul me-2"></i>Add New Sub-Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-add-subcategory">
                    <div class="mb-3">
                        <label for="field_1150" class="form-label-aw">Parent Category</label>
                        <select class="form-select" name="category_id" required autocomplete="off" id="field_1150">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="field_1151" class="form-label-aw">Sub-Category Name</label>
                        <input type="text" class="form-control-aw" name="name" placeholder="e.g. Lipid Profile" required autocomplete="off" id="field_1151">
                    </div>
                    <div class="mb-3">
                        <label for="field_1152" class="form-label-aw">Description (Optional)</label>
                        <textarea rows="3" class="form-control-aw" name="description" placeholder="Details..." autocomplete="off" id="field_1152"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-save-subcategory"><i class="fa fa-check"></i> Save Sub-Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sub-Category Modal -->
<div class="modal fade modal-aw" id="modal-edit-subcategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Sub-Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-edit-subcategory">
                    <input type="hidden" id="edit-id" name="name_1153">
                    <div class="mb-3">
                        <label for="edit-category_id" class="form-label-aw">Parent Category</label>
                        <select class="form-select" id="edit-category_id" name="category_id" required autocomplete="off">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-name" class="form-label-aw">Sub-Category Name</label>
                        <input type="text" class="form-control-aw" id="edit-name" name="name" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label-aw">Description</label>
                        <textarea rows="3" class="form-control-aw" id="edit-description" name="description" autocomplete="off"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-update-subcategory"><i class="fa fa-check"></i> Update Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Sub-Category Modal -->
<div class="modal fade modal-aw" id="modal-delete-subcategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-triangle-exclamation me-2"></i>Delete Sub-Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="color:var(--text-muted);">Are you sure you want to remove: <strong id="delete-sub-name" style="color:#dc2626;"></strong>?</p>
                <input type="hidden" id="delete-id" name="name_1154">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-danger" id="btn-confirm-delete-subcategory">Delete Permanently</button>
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

		  // Initialize DataTables for Sub-Categories
		  var subcategoriesTable = $('#subcategories-table').DataTable({
			  dom: "<'row mb-3'<'col-sm-12 col-md-6'l>>" +
				   "<'row'<'col-sm-12'tr>>" +
				   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			  pageLength: 10,
			  lengthMenu: [5, 10, 25, 50, 100],
			  ordering: false,
			  language: {
				  lengthMenu: "Show _MENU_ records",
				  info: "Showing _START_ to _END_ of _TOTAL_ sub-categories",
				  infoEmpty: "Showing 0 to 0 of 0 sub-categories",
				  infoFiltered: "(filtered from _MAX_ total sub-categories)",
				  emptyTable: "No sub-categories found.",
				  paginate: {
					  previous: "<i class='fa fa-angle-left'></i>",
					  next: "<i class='fa fa-angle-right'></i>"
				  }
			  }
		  });
		  $("#subcategory-search").on("keyup", function() {
			  subcategoriesTable.search($(this).val()).draw();
		  });

		  // Save
		  $('#btn-save-subcategory').click(function() {
              let btn = $(this);
              if (btn.prop('disabled')) return;
              
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...');
              
			  $.post("{{ route('sub-categories.store') }}", $('#form-add-subcategory').serialize(), function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
                  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Error saving sub-category.");
                  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Save Sub-Category');
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
              let btn = $(this);
              if (btn.prop('disabled')) return;
              
			  let id = $('#edit-id').val();
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');

			  $.ajax({
                  url: "/sub-categories/" + id,
                  type: 'PUT',
                  data: $('#form-edit-subcategory').serialize(),
                  success: function(response) {
                      alert(response.success);
                      location.reload();
                  },
                  error: function(xhr) {
                      alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Error updating sub-category.");
                      btn.prop('disabled', false).html('<i class="fa fa-check"></i> Update Changes');
                  }
              });
		  });

		  // Delete
		  $(document).on('click', '.btn-delete', function() {
			  $('#delete-id').val($(this).data('id'));
			  $('#delete-sub-name').text($(this).data('name'));
		  });

		  $('#btn-confirm-delete-subcategory').click(function() {
			  let btn = $(this);
			  if (btn.prop('disabled')) return;
			  
			  let id = $('#delete-id').val();
			  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Deleting...');
			  
			  $.ajax({
				  url: "/sub-categories/" + id,
				  type: 'DELETE',
				  success: function(response) {
					  alert(response.success);
					  location.reload();
				  },
				  error: function(xhr) {
					  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to delete sub-category.");
					  btn.prop('disabled', false).html('Delete Permanently');
				  }
			  });
		  });
	  });
  </script>
@endpush

@endsection



