@extends('layouts.app')
@section('title', ' | Lab Tests')
@section('page-title', 'Lab Tests (Billing)')
@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-flask"></i></div>
        <div>
            <div>Lab Tests (Billing)</div>
            <div style="font-size:13px;font-weight:400;color:var(--text-muted);margin-top:2px;">Manage all laboratory tests &amp; pricing</div>
        </div>
    </div>
    <button type="button" class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-test">
        <i class="fa fa-plus"></i> Add New Test
    </button>
</div>
<style>
    .patients-table-container {
        border-radius: 0;
        overflow: hidden;
        border: none;
        margin: 0;
        background: transparent;
    }

    .table-patients {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .table-patients thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-patients tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-patients tbody tr:last-child {
        border-bottom: none;
    }

    .table-patients tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-patients tbody td {
        padding: 16px 24px;
        vertical-align: middle;
        font-size: 14px;
        color: #334155;
    }

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
        text-decoration: none;
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

    .btn-icon-circle.sliders:hover {
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
        .patients-table-container { border: none; margin: 0; background: transparent; }
        .table-patients thead { display: none; }
        .table-patients tbody tr { 
            display: block; 
            border: 1px solid #e2e8f0; 
            margin-bottom: 16px; 
            border-radius: 16px; 
            padding: 16px; 
            background: #fff !important; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
        }
        .table-patients tbody td { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border: none !important; 
            padding: 12px 0 !important; 
            text-align: right; 
            border-bottom: 1px dashed #e2e8f0 !important; 
        }
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
</style>

<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-list" style="color:var(--primary);"></i> Laboratory Tests</div>
        <div style="position:relative;">
            <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
            <input type="text" id="test-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search tests..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive">
            <div class="patients-table-container">
                <table class="table-patients" id="tests-table">
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
                            <td data-label="ID"><span class="badge-aw" style="background:#f1f5f9;color:#475569;font-family:monospace;font-size:12px;padding:6px 10px;border-radius:6px;border:1px solid #e2e8f0;">#{{ $test->id }}</span></td>
                            <td data-label="Test Name" style="font-weight:600; color:#1e293b;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div class="cat-icon-box" style="width:32px;height:32px;font-size:14px;"><i class="fa fa-flask"></i></div>
                                    {{ $test->name }}
                                </div>
                            </td>
                            <td data-label="Price" style="font-weight:600;color:#3b82f6;">₹{{ number_format($test->price, 2) }}</td>
                            <td data-label="Description" style="color:#64748b;">{{ $test->description ?? '-' }}</td>
                            <td data-label="Action" class="text-end">
                                <div class="action-btn-group">
                                    <button class="btn-icon-circle edit btn-edit"
                                        data-id="{{ $test->id }}"
                                        data-name="{{ $test->name }}"
                                        data-price="{{ $test->price }}"
                                        data-description="{{ $test->description }}"
                                        data-bs-toggle="modal" data-bs-target="#modal-edit-test"
                                        title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </button>
                                    <a href="{{ route('test-parameters.index') }}" class="btn-icon-circle sliders" title="Parameters"><i class="fa fa-sliders"></i></a>
                                    <button class="btn-icon-circle delete btn-delete"
                                        data-id="{{ $test->id }}"
                                        data-name="{{ $test->name }}"
                                        data-bs-toggle="modal" data-bs-target="#modal-delete-test"
                                        title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding:48px;color:var(--text-muted);">
                                <i class="fa fa-flask" style="font-size:40px;display:block;margin-bottom:12px;opacity:0.4;"></i>
                                <span style="font-size:15px;">No laboratory tests found.</span>
                            </td>
                        </tr>
                        @endforelse
                        <tr class="no-results-row" style="display: none;">
                            <td colspan="5" class="text-center py-5">
                                <div style="color:var(--text-muted);">
                                    <i class="fa fa-search fa-3x mb-3" style="opacity: 0.5;"></i>
                                    <br>
                                    <span style="font-size:15px;">No matching tests found.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Test Modal -->
<div class="modal fade modal-aw" id="modal-add-test" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-flask me-2"></i>Register New Laboratory Test</h5>
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
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-save-test"><i class="fa fa-check"></i> Save Test</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Test Modal -->
<div class="modal fade modal-aw" id="modal-edit-test" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Laboratory Test</h5>
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
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-update-test"><i class="fa fa-check"></i> Update Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade modal-aw" id="modal-delete-test" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-triangle-exclamation me-2"></i>Delete Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="color:var(--text-muted);">Are you sure you want to remove the test: <strong id="delete-test-name" style="color:#dc2626;"></strong>?</p>
                <p style="font-size:12px;color:var(--text-muted);">This action cannot be undone and may affect existing patient records.</p>
                <input type="hidden" id="delete-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-danger" id="btn-confirm-delete">Delete Permanently</button>
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

		  // Live Search for Tests
		  $("#test-search").on("keyup", function() {
			  var value = $(this).val().toLowerCase();
			  let rows = $('#tests-table tbody tr:not(.no-results-row)');
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

		  // Save Test
		  $('#btn-save-test').click(function() {
			  let btn = $(this);
			  if (btn.prop('disabled')) return;
			  
			  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...');
			  let formData = $('#form-add-test').serialize();
			  
			  $.post("{{ route('lab-tests.store') }}", formData, function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
				  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to save test. Please check inputs.");
				  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Save Test');
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
			  let btn = $(this);
			  if (btn.prop('disabled')) return;
			  
			  let id = $('#edit-id').val();
			  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');
 
			  $.post("/lab-tests/update/" + id, $('#form-edit-test').serialize(), function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
				  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to update test. Please check inputs.");
				  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Update Changes');
			  });
		  });

		  // Delete Test
		  $(document).on('click', '.btn-delete', function() {
			  $('#delete-id').val($(this).data('id'));
			  $('#delete-test-name').text($(this).data('name'));
		  });

		  $('#btn-confirm-delete').click(function() {
			  let btn = $(this);
			  if (btn.prop('disabled')) return;
			  
			  let id = $('#delete-id').val();
			  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Deleting...');

			  $.ajax({
				  url: "/lab-tests/" + id,
				  type: 'DELETE',
				  success: function(response) {
					  alert(response.success);
					  location.reload();
				  },
				  error: function(xhr) {
					  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to delete test.");
					  btn.prop('disabled', false).html('Delete Permanently');
				  }
			  });
		  });
	  });
  </script>
@endpush

@endsection
