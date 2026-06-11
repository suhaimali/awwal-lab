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
    .cat-icon-box {
        width: 40px; height: 40px; border-radius: 12px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #3b82f6; display: inline-flex; align-items: center; justify-content: center;
        font-size: 16px; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15);
    }
    .action-btn-group { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-icon-circle {
        width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
        border: 1px solid #e2e8f0; background: #fff; color: #64748b; transition: all 0.2s ease; cursor: pointer; outline: none; text-decoration: none;
    }
    .btn-icon-circle:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .btn-icon-circle.edit:hover { color: #3b82f6; border-color: #bfdbfe; background: #eff6ff; }
    .btn-icon-circle.sliders:hover { color: #10b981; border-color: #a7f3d0; background: #ecfdf5; }
    .btn-icon-circle.delete:hover { color: #ef4444; border-color: #fecaca; background: #fef2f2; }
    @media (max-width: 767px) {
        .table-modern .text-end { justify-content: center; width: 100%; border-top: 1px solid #f1f5f9 !important; margin-top: 15px; padding-top: 15px !important; }
        .action-btn-group { width: 100%; justify-content: center; gap: 16px; }
    }
</style>

<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-list" style="color:var(--primary);"></i> Laboratory Tests</div>
        <div style="position:relative;">
            <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
            <input type="text" id="test-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search tests..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="name_1155">
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">
            
                <table class="table-modern" id="tests-table">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Test Name</th>
                            <th>Price</th>
                            <th>Payment Mode</th>
                            <th>Description</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $test)
                        <tr>
                            <td data-label="SL No"><span class="badge-aw" style="background:#f1f5f9;color:#475569;font-family:monospace;font-size:12px;padding:6px 10px;border-radius:6px;border:1px solid #e2e8f0;">#{{ $test->id }}</span></td>
                            <td data-label="Test Name" style="font-weight:600; color:#1e293b;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div class="cat-icon-box" style="width:32px;height:32px;font-size:14px;"><i class="fa fa-flask"></i></div>
                                    {{ $test->name }}
                                </div>
                            </td>
                            <td data-label="Price" style="font-weight:600;color:#3b82f6;">₹{{ number_format($test->price, 2) }}</td>
                            <td data-label="Payment Mode">{{ $test->payment_method }}</td>
                            <td data-label="Description" style="color:#64748b;">{{ $test->description ?? '-' }}</td>
                            <td data-label="Action" class="text-end">
                                <div class="action-btn-group">
                                    <button class="btn-icon-circle edit btn-edit"
                                        data-id="{{ $test->id }}"
                                        data-name="{{ $test->name }}"
                                        data-price="{{ $test->price }}"
                                        data-payment_method="{{ $test->payment_method }}"
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
                            <td colspan="6" class="text-center" style="padding:48px;color:var(--text-muted);">
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <i class="fa fa-flask" style="font-size:40px;margin-bottom:12px;opacity:0.4;"></i>
                                    <span style="font-size:15px;">No laboratory tests found.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        <tr class="no-results-row" style="display: none;">
                            <td colspan="5" class="text-center py-5">
                                <div style="color:var(--text-muted);">
                                    <i class="fa fa-search fa-3x mb-3" style="opacity: 0.5;"></i>
                                    <br>
                                    <span style="font-size:15px; display: block; text-align: center;">No matching tests found.</span>
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
					<label for="field_1156" class="form-label">Test Name</label>
					<input type="text" class="form-control" name="name" placeholder="e.g. Blood Sugar Level" required autocomplete="off" id="field_1156">
				</div>
				<div class="form-group">
					<label for="field_1157" class="form-label">Price (₹)</label>
					<input type="number" class="form-control" name="price" placeholder="e.g. 500" required autocomplete="off" id="field_1157">
				</div>

				<div class="form-group">
					<label for="field_1161" class="form-label">Payment Mode</label>
					<select class="form-select" name="payment_method" required autocomplete="off" id="field_1161">
						<option value="Cash">Cash</option>
						<option value="Card">Card</option>
						<option value="UPI">UPI</option>
						<option value="Net Banking">Net Banking</option>
					</select>
				</div>

				<div class="form-group">
					<label for="field_1158" class="form-label">Description (Optional)</label>
					<textarea rows="2" class="form-control" name="description" placeholder="Short details about the test..." autocomplete="off" id="field_1158"></textarea>
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
				<input type="hidden" id="edit-id" name="name_1159">
				<div class="form-group">
					<label for="edit-name" class="form-label">Test Name</label>
					<input type="text" class="form-control" id="edit-name" name="name" required autocomplete="off">
				</div>
				<div class="form-group">
					<label for="edit-price" class="form-label">Price (₹)</label>
					<input type="number" class="form-control" id="edit-price" name="price" required autocomplete="off">
				</div>

				<div class="form-group">
					<label for="edit-payment_method" class="form-label">Payment Mode</label>
					<select class="form-select" id="edit-payment_method" name="payment_method" required autocomplete="off">
						<option value="Cash">Cash</option>
						<option value="Card">Card</option>
						<option value="UPI">UPI</option>
						<option value="Net Banking">Net Banking</option>
					</select>
				</div>

				<div class="form-group">
					<label for="edit-description" class="form-label">Description</label>
					<textarea rows="2" class="form-control" id="edit-description" name="description" autocomplete="off"></textarea>
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
                <input type="hidden" id="delete-id" name="name_1160">
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

		  // Initialize DataTables for Tests
		  var testsTable = $('#tests-table').DataTable({
			  dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-none'f>>" +
				   "<'row'<'col-sm-12'tr>>" +
				   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			  pageLength: 10,
			  lengthMenu: [5, 10, 25, 50, 100],
			  ordering: false,
			  language: {
				  lengthMenu: "Show _MENU_ records",
				  info: "Showing _START_ to _END_ of _TOTAL_ tests",
				  infoEmpty: "Showing 0 to 0 of 0 tests",
				  infoFiltered: "(filtered from _MAX_ total tests)",
				  paginate: {
					  previous: "<i class='fa fa-angle-left'></i>",
					  next: "<i class='fa fa-angle-right'></i>"
				  }
			  }
		  });
		  $("#test-search").on("keyup", function() {
			  testsTable.search($(this).val()).draw();
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
			  $('#edit-payment_method').val($(this).data('payment_method'));
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



