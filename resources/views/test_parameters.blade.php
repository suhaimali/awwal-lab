@extends('layouts.app')
@section('title', ' | Test Parameters')
@section('page-title', 'Test Parameters')
@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-sliders"></i></div>
        <div>
            <div>Test Parameters</div>
            <div style="font-size:13px;font-weight:400;color:var(--text-muted);margin-top:2px;">Configure reference intervals &amp; clinical logic</div>
        </div>
    </div>
    <a href="{{ route('lab-tests.index') }}" class="btn-aw-outline">
        <i class="fa fa-list"></i> Manage Billing &amp; Prices
    </a>
</div>
<style>


    @media (max-width: 767px) {
    
        .table-patients tbody td:last-child { border-bottom: none !important; }
        .table-patients tbody td::before { content: attr(data-label); font-weight: 700; text-align: left; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .table-patients .text-end { justify-content: center; width: 100%; border-top: 1px solid #f1f5f9 !important; margin-top: 15px; padding-top: 15px !important; }
        .table-patients .btn-edit-params { width: 100%; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 600; }
    }
</style>

<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-sliders" style="color:var(--primary);"></i> Reference Intervals & Clinical Logic</div>
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ count($tests) }} tests configured</span>
            <div style="position:relative;">
                <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                <input type="text" id="param-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:200px;" placeholder="Search tests..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="name_1161">
            </div>
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">
            
                <table class="table-modern" id="parameters-table">
                    <thead>
                        <tr>
                            <th>Sl. No.</th>
                            <th>Test Name</th>
                            <th>Unit</th>
                            <th>Male Reference</th>
                            <th>Female Reference</th>
                            <th>Critical (L/H)</th>
                            <th>Type</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $i => $test)
                        <tr>
                            <td data-label="Sl. No."><span class="badge-aw badge-blue">{{ $i + 1 }}</span></td>
                            <td data-label="Test Name" style="font-weight:600;">{{ $test->name }}</td>
                            <td data-label="Unit"><span class="badge-aw badge-blue">{{ $test->parameter->unit ?? 'Not Set' }}</span></td>
                            <td data-label="Male Reference" style="font-size:12px;color:var(--text-muted);">{{ Str::limit($test->parameter->male_reference ?? '-', 30) }}</td>
                            <td data-label="Female Reference" style="font-size:12px;color:var(--text-muted);">{{ Str::limit($test->parameter->female_reference ?? '-', 30) }}</td>
                            <td data-label="Critical (L/H)">
                                @if(isset($test->parameter->critical_low) || isset($test->parameter->critical_high))
                                    <span class="badge-aw badge-red">{{ $test->parameter->critical_low ?? 'N/A' }} / {{ $test->parameter->critical_high ?? 'N/A' }}</span>
                                @else
                                    <span class="badge-aw badge-gray">Not Set</span>
                                @endif
                            </td>
                            <td data-label="Type">
                                @if($test->parameter && $test->parameter->is_immunoassay)
                                    <span class="badge-aw badge-green">Immunoassay</span>
                                @else
                                    <span class="badge-aw badge-purple">Standard</span>
                                @endif
                            </td>
                            <td data-label="Action" class="text-end">
                                <button class="btn-aw-primary btn-aw-sm btn-edit-params"
                                    data-id="{{ $test->id }}"
                                    data-name="{{ $test->name }}"
                                    data-unit="{{ $test->parameter->unit ?? '' }}"
                                    data-male_ref="{{ $test->parameter->male_reference ?? '' }}"
                                    data-female_ref="{{ $test->parameter->female_reference ?? '' }}"
                                    data-male_min="{{ $test->parameter->male_min ?? '' }}"
                                    data-male_max="{{ $test->parameter->male_max ?? '' }}"
                                    data-female_min="{{ $test->parameter->female_min ?? '' }}"
                                    data-female_max="{{ $test->parameter->female_max ?? '' }}"
                                    data-critical_low="{{ $test->parameter->critical_low ?? '' }}"
                                    data-critical_high="{{ $test->parameter->critical_high ?? '' }}"
                                    data-is_immuno="{{ $test->parameter->is_immunoassay ?? 0 }}"
                                    data-bio_ref="{{ $test->parameter->biological_reference ?? '' }}"
                                    data-bs-toggle="modal" data-bs-target="#modal-edit-params"
                                    title="Configure Parameters">
                                    <i class="fa fa-sliders"></i> Configure
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div style="color:var(--text-muted);">
                                    <i class="fa fa-sliders fa-3x mb-3" style="opacity: 0.5;"></i>
                                    <br>
                                    <span style="font-size:15px;">No tests found.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        <tr class="no-results-row" style="display: none;">
                            <td colspan="8" class="text-center py-5">
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

<!-- Setup Modal -->
<div class="modal fade modal-aw" id="modal-edit-params" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-sliders me-2"></i>Clinical Setup: <span id="setup-test-name" style="color:#ffffff;"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
		  <div class="modal-body">
			<form id="form-setup-params">
				<input type="hidden" name="lab_test_id" id="setup-test-id">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
								<label for="setup-unit" class="form-label-aw">Measurement Unit</label>
								<select class="form-select" name="unit" id="setup-unit" autocomplete="off">
									<option value="">Select unit</option>
									@foreach($units as $unit)
										<option value="{{ $unit->name }}">{{ $unit->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="setup-is-immuno" class="form-label-aw">Calculation Logic</label>
							<select class="form-select" name="is_immunoassay" id="setup-is-immuno" autocomplete="off">
								<option value="0">Standard (Min-Max Flagging)</option>
								<option value="1">Immunoassay (Neg/Bord/Pos Flagging)</option>
							</select>
						</div>
					</div>
					</div>

					<h5 class="text-secondary border-bottom pb-2 mt-4"><i class="fa fa-heart-pulse me-2"></i>Add New Parameter</h5>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="setup-critical-low" class="form-label-aw">Critical Low</label>
								<input type="number" step="0.01" class="form-control-aw" name="critical_low" id="setup-critical-low" placeholder="e.g. 50" autocomplete="off">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="setup-critical-high" class="form-label-aw">Critical High</label>
								<input type="number" step="0.01" class="form-control-aw" name="critical_high" id="setup-critical-high" placeholder="e.g. 200" autocomplete="off">
							</div>
						</div>
					</div>

					<h5 class="text-primary border-bottom pb-2 mt-4"><i class="fa fa-flask me-2"></i>Reference Intervals (Biological)</h5>
				<div class="row g-3">
					<div class="col-md-4">
						<div class="card border-0 shadow-sm" style="background:#f8fafc; border-radius:12px; border-top: 3px solid var(--primary) !important;">
							<div class="card-header bg-transparent border-0 pb-0 pt-3 px-3"><h6 class="mb-0 fw-bold" style="color:var(--primary);"><i class="fa fa-mars me-2"></i>Male Parameters</h6></div>
							<div class="card-body p-3">
								<div class="form-group mb-2">
									<label for="setup-male-ref" class="form-label-aw" style="font-size: 11px;">Reference Text</label>
									<textarea rows="2" class="form-control-aw" name="male_reference" id="setup-male-ref" placeholder="e.g. 70 - 110 mg/dl" autocomplete="off"></textarea>
								</div>
								<div class="row g-2">
									<div class="col-6">
										<label for="setup-male-min" class="form-label-aw" style="font-size: 10px;">Min</label>
										<input type="number" step="0.01" class="form-control-aw" name="male_min" id="setup-male-min" autocomplete="off">
									</div>
									<div class="col-6">
										<label for="setup-male-max" class="form-label-aw" style="font-size: 10px;">Max</label>
										<input type="number" step="0.01" class="form-control-aw" name="male_max" id="setup-male-max" autocomplete="off">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card border-0 shadow-sm" style="background:#f8fafc; border-radius:12px; border-top: 3px solid #06b6d4 !important;">
							<div class="card-header bg-transparent border-0 pb-0 pt-3 px-3"><h6 class="mb-0 fw-bold" style="color:#06b6d4;"><i class="fa fa-venus me-2"></i>Female Parameters</h6></div>
							<div class="card-body p-3">
								<div class="form-group mb-2">
									<label for="setup-female-ref" class="form-label-aw" style="font-size: 11px;">Reference Text</label>
									<textarea rows="2" class="form-control-aw" name="female_reference" id="setup-female-ref" placeholder="e.g. 60 - 100 mg/dl" autocomplete="off"></textarea>
								</div>
								<div class="row g-2">
									<div class="col-6">
										<label for="setup-female-min" class="form-label-aw" style="font-size: 10px;">Min</label>
										<input type="number" step="0.01" class="form-control-aw" name="female_min" id="setup-female-min" autocomplete="off">
									</div>
									<div class="col-6">
										<label for="setup-female-max" class="form-label-aw" style="font-size: 10px;">Max</label>
										<input type="number" step="0.01" class="form-control-aw" name="female_max" id="setup-female-max" autocomplete="off">
									</div>
								</div>
							</div>
						</div>
					</div>
                    <div class="col-md-4">
						<div class="card border-0 shadow-sm" style="background:#f8fafc; border-radius:12px; border-top: 3px solid #10b981 !important;">
							<div class="card-header bg-transparent border-0 pb-0 pt-3 px-3"><h6 class="mb-0 fw-bold" style="color:#10b981;"><i class="fa fa-universal-access me-2"></i>General Bio-Ref</h6></div>
							<div class="card-body p-3">
								<div class="form-group mb-0">
									<label for="setup-bio-ref" class="form-label-aw" style="font-size: 11px;">Common Bio Reference</label>
									<textarea rows="5" class="form-control-aw" name="biological_reference" id="setup-bio-ref" placeholder="Enter general biological reference interval for all genders..." autocomplete="off"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>

            <h5 class="text-secondary border-bottom pb-2 mt-4"><i class="fa fa-list-ol me-2"></i>Advanced Reference Intervals</h5>
            <div class="table-responsive-modern mt-3 mb-3" style="max-height: 250px; overflow-y: auto;">
                <table class="table-modern" id="advanced-intervals-table">
                    <thead>
                        <tr>
                            <th>Gender</th>
                            <th>Age Min/Max</th>
                            <th>Reference Text</th>
                            <th>Min/Max Value</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows injected by JS -->
                    </tbody>
                </table>
            </div>
            
            <div class="card border-0 shadow-sm" style="background:#f8fafc; border-radius:12px; border-top: 3px solid #64748b !important;">
                <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3"><h6 class="mb-0 fw-bold" style="color:#64748b;"><i class="fa fa-plus-circle me-2"></i><span id="interval-form-title">Add Advanced Interval</span></h6></div>
                <div class="card-body p-3">
                    <form id="form-add-interval">
                        <input type="hidden" name="lab_test_id" id="interval-test-id">
                        <input type="hidden" name="interval_id" id="interval-id">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label for="field_1004" class="form-label-aw" style="font-size: 10px;">Gender</label>
                                <select class="form-select" name="gender" autocomplete="off" required id="field_1004">
                                    <option value="Any">Any</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label for="field_1005" class="form-label-aw" style="font-size: 10px;">Age Min</label>
                                <input type="number" step="0.1" class="form-control-aw" name="age_min" autocomplete="off" id="field_1005">
                            </div>
                            <div class="col-md-1">
                                <label for="field_1006" class="form-label-aw" style="font-size: 10px;">Age Max</label>
                                <input type="number" step="0.1" class="form-control-aw" name="age_max" autocomplete="off" id="field_1006">
                            </div>
                            <div class="col-md-2">
                                <label for="field_1007" class="form-label-aw" style="font-size: 10px;">Age Type</label>
                                <select class="form-select" name="age_type" autocomplete="off" id="field_1007">
                                    <option value="Years">Years</option>
                                    <option value="Months">Months</option>
                                    <option value="Days">Days</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="field_1008" class="form-label-aw" style="font-size: 10px;">Reference Text</label>
                                <input type="text" class="form-control-aw" name="reference_text" autocomplete="off" id="field_1008">
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <div class="flex-grow-1">
                                        <label for="field_1009" class="form-label-aw" style="font-size: 10px;">Min Val</label>
                                        <input type="number" step="0.01" class="form-control-aw" name="min_value" autocomplete="off" id="field_1009">
                                    </div>
                                    <div class="flex-grow-1">
                                        <label for="field_1010" class="form-label-aw" style="font-size: 10px;">Max Val</label>
                                        <input type="number" step="0.01" class="form-control-aw" name="max_value" autocomplete="off" id="field_1010">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end mt-2">
                                <button type="button" class="btn-aw-outline btn-aw-sm" id="btn-cancel-edit-interval" style="display:none;"><i class="fa fa-times me-1"></i> Cancel Edit</button>
                                <button type="submit" class="btn-aw-primary btn-aw-sm" id="btn-submit-interval"><i class="fa fa-plus me-1"></i> Add Interval</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

		  </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-save-params"><i class="fa fa-check"></i> Update Clinical Data</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
@push('scripts')
  <script>
	  $(document).ready(function() {
		  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

		  // Initialize DataTables for Parameters
		  var parametersTable = $('#parameters-table').DataTable({
			  dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-none'f>>" +
				   "<'row'<'col-sm-12'tr>>" +
				   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			  pageLength: 10,
			  lengthMenu: [5, 10, 25, 50, 100],
			  ordering: false,
			  language: {
				  lengthMenu: "Show _MENU_ records",
				  info: "Showing _START_ to _END_ of _TOTAL_ parameters",
				  infoEmpty: "Showing 0 to 0 of 0 parameters",
				  infoFiltered: "(filtered from _MAX_ total parameters)",
				  paginate: {
					  previous: "<i class='fa fa-angle-left'></i>",
					  next: "<i class='fa fa-angle-right'></i>"
				  }
			  }
		  });
		  $('#param-search').on('keyup', function() {
			  parametersTable.search($(this).val()).draw();
		  });

		  // Populate edit modal
		  $(document).on('click', '.btn-edit-params', function() {
			  $('#setup-test-id').val($(this).data('id'));
			  $('#setup-test-name').text($(this).data('name'));
			  $('#setup-unit').val($(this).data('unit'));
			  $('#setup-male-ref').val($(this).data('male_ref'));
			  $('#setup-female-ref').val($(this).data('female_ref'));
			  $('#setup-male-min').val($(this).data('male_min'));
			  $('#setup-male-max').val($(this).data('male_max'));
			  $('#setup-female-min').val($(this).data('female_min'));
			  $('#setup-female-max').val($(this).data('female_max'));
			  $('#setup-critical-low').val($(this).data('critical_low'));
			  $('#setup-critical-high').val($(this).data('critical_high'));
			  $('#setup-is-immuno').val($(this).data('is_immuno'));
			  $('#setup-bio-ref').val($(this).data('bio_ref'));

              // Load Intervals dynamically
              let testId = $(this).data('id');
              $('#interval-test-id').val(testId);
              let tbody = $('#advanced-intervals-table tbody');
              tbody.html('<tr><td colspan="5" class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin me-2"></i>Loading intervals...</td></tr>');
              
              $.get("/test-parameters/" + testId + "/intervals", function(intervals) {
                  tbody.empty();
                  if(intervals && intervals.length > 0) {
                      intervals.forEach(function(inv) {
                          tbody.append(`
                              <tr>
                                  <td data-label="Gender" style="font-size:12px;font-weight:600;"><span class="badge-aw bg-light text-dark border">${inv.gender}</span></td>
                                  <td data-label="Age Min/Max" style="font-size:12px;">${inv.age_min !== null ? inv.age_min : '-'} to ${inv.age_max !== null ? inv.age_max : '-'} ${inv.age_type || 'Years'}</td>
                                  <td data-label="Reference Text" style="font-size:12px;">${inv.reference_text || '-'}</td>
                                  <td data-label="Min/Max Value" style="font-size:12px;">${inv.min_value !== null ? inv.min_value : '-'} / ${inv.max_value !== null ? inv.max_value : '-'}</td>
                                  <td data-label="Action" class="text-end">
                                      <button type="button" class="btn-aw-success btn-aw-sm btn-edit-interval" data-id="${inv.id}" data-gender="${inv.gender}" data-age_min="${inv.age_min !== null ? inv.age_min : ''}" data-age_max="${inv.age_max !== null ? inv.age_max : ''}" data-age_type="${inv.age_type || 'Years'}" data-ref_text="${inv.reference_text || ''}" data-min_val="${inv.min_value !== null ? inv.min_value : ''}" data-max_val="${inv.max_value !== null ? inv.max_value : ''}"><i class="fa fa-edit"></i></button>
                                      <button type="button" class="btn-aw-danger btn-aw-sm btn-delete-interval" data-id="${inv.id}"><i class="fa fa-trash"></i></button>
                                  </td>
                              </tr>
                          `);
                      });
                  } else {
                      tbody.append('<tr><td colspan="5" class="text-center text-muted py-4"><i class="fa fa-info-circle me-2"></i>No advanced intervals configured.</td></tr>');
                  }
              }).fail(function() {
                  tbody.html('<tr><td colspan="5" class="text-center text-danger py-4"><i class="fa fa-triangle-exclamation me-2"></i>Failed to load intervals.</td></tr>');
              });
		  });

          // Edit Interval Row Clicked
          $(document).on('click', '.btn-edit-interval', function() {
              let id = $(this).data('id');
              let gender = $(this).data('gender');
              let ageMin = $(this).data('age_min');
              let ageMax = $(this).data('age_max');
              let ageType = $(this).data('age_type');
              let refText = $(this).data('ref_text');
              let minVal = $(this).data('min_val');
              let maxVal = $(this).data('max_val');

              $('#interval-id').val(id);
              $('#form-add-interval select[name="gender"]').val(gender);
              $('#form-add-interval input[name="age_min"]').val(ageMin);
              $('#form-add-interval input[name="age_max"]').val(ageMax);
              $('#form-add-interval select[name="age_type"]').val(ageType);
              $('#form-add-interval input[name="reference_text"]').val(refText);
              $('#form-add-interval input[name="min_value"]').val(minVal);
              $('#form-add-interval input[name="max_value"]').val(maxVal);

              $('#interval-form-title').text('Edit Advanced Interval');
              $('#btn-submit-interval').html('<i class="fa fa-check me-1"></i> Update Interval');
              $('#btn-cancel-edit-interval').show();
          });

          // Cancel Edit Clicked
          $('#btn-cancel-edit-interval').click(function() {
              resetIntervalForm();
          });

          function resetIntervalForm() {
              $('#interval-id').val('');
              $('#form-add-interval')[0].reset();
              $('#interval-form-title').text('Add Advanced Interval');
              $('#btn-submit-interval').html('<i class="fa fa-plus me-1"></i> Add Interval');
              $('#btn-cancel-edit-interval').hide();
          }

          // Reset the form when setup modal opens/reloads to clear any edit state
          $('#modal-edit-params').on('show.bs.modal', function() {
              resetIntervalForm();
          });

          // Add/Update Interval
          $('#form-add-interval').submit(function(e) {
              e.preventDefault();
              let btn = $('#btn-submit-interval');
              let isEdit = $('#interval-id').val() ? true : false;
              btn.prop('disabled', true).html(isEdit ? '<i class="fa fa-spinner fa-spin me-1"></i> Updating...' : '<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
              let testId = $('#interval-test-id').val();
              $.post("/test-parameters/" + testId + "/intervals", $(this).serialize(), function(res) {
                  location.reload();
              }).fail(function() {
                  alert(isEdit ? 'Error updating interval.' : 'Error saving interval.');
                  btn.prop('disabled', false).html(isEdit ? '<i class="fa fa-check me-1"></i> Update Interval' : '<i class="fa fa-plus me-1"></i> Add Interval');
              });
          });

          // Delete Interval
          $(document).on('click', '.btn-delete-interval', function() {
              if(confirm('Are you sure you want to remove this advanced reference interval?')) {
                  let id = $(this).data('id');
                  $.ajax({
                      url: "/test-parameters/intervals/" + id,
                      type: 'DELETE',
                      success: function() { location.reload(); },
                      error: function() { alert('Error deleting interval.'); }
                  });
              }
          });

		  $('#btn-save-params').click(function() {
              let btn = $(this);
              if (btn.prop('disabled')) return;
              
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');
			  $.post("{{ route('test-parameters.store') }}", $('#form-setup-params').serialize(), function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function(xhr) {
                  alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Error updating parameters.");
                  btn.prop('disabled', false).html('<i class="fa fa-check"></i> Update Clinical Data');
              });
		  });
	  });
  </script>
@endpush

@endsection



