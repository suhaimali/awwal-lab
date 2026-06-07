@extends('layouts.app')
@section('title', ' | Master Categories')
@section('page-title', 'Master Categories')
@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-tags"></i></div>
        <div>
            <div>Master Categories</div>
            <div style="font-size:13px;font-weight:400;color:var(--text-muted);margin-top:2px;">Manage top-level test categories</div>
        </div>
    </div>
    <button type="button" class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-category">
        <i class="fa fa-plus"></i> Add New Category
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
        .cat-name-cell { justify-content: flex-end; }
    }
</style>

<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-list" style="color:var(--primary);"></i> All Categories</div>
        <div style="position:relative;">
            <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
            <input type="text" id="category-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search categories..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="name_1016">
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">
            
                <table class="table-modern" id="categories-table">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Created Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td data-label="SL No">
                                <span class="badge-aw" style="background:#f1f5f9;color:#475569;font-family:monospace;font-size:12px;padding:6px 10px;border-radius:6px;border:1px solid #e2e8f0;">#{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td data-label="Category Name">
                                <div class="cat-name-cell">
                                    <div class="cat-icon-box">
                                        <i class="fa fa-tag"></i>
                                    </div>
                                    <span>{{ $category->name }}</span>
                                </div>
                            </td>
                            <td data-label="Description" style="color:#64748b;font-size:13px;line-height:1.5;">{{ Str::limit($category->description, 50, '...') ?? '-' }}</td>
                            <td data-label="Created Date" style="color:#64748b;font-size:13px;"><i class="fa fa-calendar-alt me-2" style="opacity:0.5;"></i>{{ $category->created_at->format('d M, Y') }}</td>
                            <td data-label="Action" class="text-end">
                                <div class="action-btn-group">
                                    <button class="btn-icon-circle edit btn-edit"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-description="{{ $category->description }}"
                                        data-bs-toggle="modal" data-bs-target="#modal-edit-category"
                                        title="Edit"><i class="fa fa-pen"></i></button>
                                    <button class="btn-icon-circle delete btn-delete"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-bs-toggle="modal" data-bs-target="#modal-delete-category"
                                        title="Delete"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding:48px;color:var(--text-muted);">
                                <i class="fa fa-tags" style="font-size:40px;display:block;margin-bottom:12px;opacity:0.4;"></i>
                                <span style="font-size:15px;">No categories found.</span>
                            </td>
                        </tr>
                        @endforelse
                        <tr class="no-results-row" style="display: none;">
                            <td colspan="5" class="text-center py-5">
                                <div style="color:var(--text-muted);">
                                    <i class="fa fa-search fa-3x mb-3" style="opacity: 0.5;"></i>
                                    <br>
                                    <span style="font-size:15px;">No matching categories found.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade modal-aw" id="modal-add-category" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-tags me-2"></i>Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-add-category">
                    <div class="mb-3">
                        <label for="field_1017" class="form-label-aw">Category Name</label>
                        <input type="text" class="form-control-aw" name="name" placeholder="e.g. Hematology" required autocomplete="off" id="field_1017">
                    </div>
                    <div class="mb-3">
                        <label for="field_1018" class="form-label-aw">Description (Optional)</label>
                        <textarea rows="3" class="form-control-aw" name="description" placeholder="Details about this category..." autocomplete="off" id="field_1018"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-save-category"><i class="fa fa-check"></i> Save Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade modal-aw" id="modal-edit-category" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-edit-category">
                    <input type="hidden" id="edit-id" name="name_1019">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label-aw">Category Name</label>
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
                <button type="button" class="btn-aw-primary" id="btn-update-category"><i class="fa fa-check"></i> Update Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade modal-aw" id="modal-delete-category" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-triangle-exclamation me-2"></i>Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="color:var(--text-muted);">Are you sure you want to remove: <strong id="delete-category-name" style="color:#dc2626;"></strong>?</p>
                <p style="font-size:12px;color:var(--text-muted);">Deleting a category may affect reports using it.</p>
                <input type="hidden" id="delete-id" name="name_1020">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-danger" id="btn-confirm-delete-category">Delete Permanently</button>
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

		  // Live Search
		  $("#category-search").on("keyup", function() {
			  var value = $(this).val().toLowerCase();
			  let rows = $('#categories-table tbody tr:not(.no-results-row)');
			  let matched = 0;
			  
			  rows.each(function() {
				  let matches = $(this).text().toLowerCase().indexOf(value) > -1;
				  $(this).toggle(matches);
				  if (matches) matched++;
			  });
			  
			  if (matched === 0) {
				  $('.no-results-row').show();
			  } else {
				  $('.no-results-row').hide();
			  }
		  });

		  // Save Category
		  $('#btn-save-category').click(function() {
			  let btn = $(this);
			  if (btn.prop('disabled')) return;
			  
			  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...');
			  let formData = $('#form-add-category').serialize();
			  
			  $.post("{{ route('categories.store') }}", formData, function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
				  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Error saving category.");
				  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Save Category');
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
			  let btn = $(this);
			  if (btn.prop('disabled')) return;
			  
			  let id = $('#edit-id').val();
			  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');
			  
			  $.ajax({
				  url: "/categories/" + id,
				  type: 'PUT',
				  data: $('#form-edit-category').serialize(),
				  success: function(response) {
					  alert(response.success);
					  location.reload();
				  },
				  error: function(xhr) {
					  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to update category.");
					  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Update Changes');
				  }
			  });
		  });

		  // Delete Category
		  $(document).on('click', '.btn-delete', function() {
			  $('#delete-id').val($(this).data('id'));
			  $('#delete-category-name').text($(this).data('name'));
		  });

		  $('#btn-confirm-delete-category').click(function() {
			  let btn = $(this);
			  if (btn.prop('disabled')) return;
			  
			  let id = $('#delete-id').val();
			  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Deleting...');
			  
			  $.ajax({
				  url: "/categories/" + id,
				  type: 'DELETE',
				  success: function(response) {
					  alert(response.success);
					  location.reload();
				  },
				  error: function(xhr) {
					  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to delete category.");
					  btn.prop('disabled', false).html('Delete Permanently');
				  }
			  });
		  });
	  });
  </script>
@endpush

@endsection


