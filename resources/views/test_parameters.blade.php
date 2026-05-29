@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Clinical Test Parameters</h4>

				</div>
                <div class="ms-auto">
                    <a href="{{ route('lab-tests.index') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-list me-1"></i> Manage Billing & Prices
                    </a>
                </div>
			</div>
		</div>

		<section class="content">
			<div class="row">
				<div class="col-12">
					<div class="box">
						<div class="box-header with-border">
							<h4 class="box-title">Configure Reference Intervals & Logic</h4>
                            <p class="mb-0 text-fade">Select a test to configure its clinical parameters and auto-flagging logic.</p>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover" id="parameters-table">
									<thead>
										<tr>
											<th>Test Name</th>
											<th>Unit</th>
											<th>Male Reference</th>
											<th>Female Reference</th>
											<th>Type</th>
											<th class="text-end">Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($tests as $test)
										<tr>
											<td class="fw-600">{{ $test->name }}</td>
											<td><span class="badge badge-primary-light">{{ $test->parameter->unit ?? 'Not Set' }}</span></td>
											<td class="fs-12 text-fade">{{ Str::limit($test->parameter->male_reference ?? '-', 30) }}</td>
											<td class="fs-12 text-fade">{{ Str::limit($test->parameter->female_reference ?? '-', 30) }}</td>
											<td>
                                                @if($test->parameter && $test->parameter->is_immunoassay)
                                                    <span class="badge badge-success">Immunoassay</span>
                                                @else
                                                    <span class="badge badge-info">Standard</span>
                                                @endif
                                            </td>
											<td class="text-end">
												<button class="btn btn-primary btn-sm btn-edit-params" 
                                                    data-id="{{ $test->id }}" 
                                                    data-name="{{ $test->name }}"
                                                    data-unit="{{ $test->parameter->unit ?? '' }}"
                                                    data-male_ref="{{ $test->parameter->male_reference ?? '' }}"
                                                    data-female_ref="{{ $test->parameter->female_reference ?? '' }}"
                                                    data-male_min="{{ $test->parameter->male_min ?? '' }}"
                                                    data-male_max="{{ $test->parameter->male_max ?? '' }}"
                                                    data-female_min="{{ $test->parameter->female_min ?? '' }}"
                                                    data-female_max="{{ $test->parameter->female_max ?? '' }}"
                                                    data-is_immuno="{{ $test->parameter->is_immunoassay ?? 0 }}"
                                                    data-bio_ref="{{ $test->parameter->biological_reference ?? '' }}"
                                                    data-bs-toggle="modal" data-bs-target="#modal-edit-params">
													<i class="fa fa-cog me-1"></i> Setup
												</button>
											</td>
										</tr>
										@endforeach
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

  <!-- Setup Modal -->
  <div class="modal center-modal fade" id="modal-edit-params" tabindex="-1">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Clinical Setup: <span id="setup-test-name" class="text-primary"></span></h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-setup-params">
				<input type="hidden" name="lab_test_id" id="setup-test-id">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Measurement Unit</label>
							<input type="text" class="form-control" name="unit" id="setup-unit" placeholder="e.g. mg/dl">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Calculation Logic</label>
							<select class="form-select" name="is_immunoassay" id="setup-is-immuno">
								<option value="0">Standard (Min-Max Flagging)</option>
								<option value="1">Immunoassay (Neg/Bord/Pos Flagging)</option>
							</select>
						</div>
					</div>
				</div>

				<h5 class="text-primary border-bottom pb-2 mt-4">Reference Intervals (Biological)</h5>
				<div class="row">
					<div class="col-md-4">
						<div class="box box-outline-primary shadow-none mb-0">
							<div class="box-header with-border p-2"><h6 class="mb-0">Male Parameters</h6></div>
							<div class="box-body p-3">
								<div class="form-group mb-2">
									<label class="form-label fs-12">Reference Text</label>
									<textarea rows="2" class="form-control" name="male_reference" id="setup-male-ref" placeholder="e.g. 70 - 110 mg/dl"></textarea>
								</div>
								<div class="row">
									<div class="col-6">
										<label class="fs-10 text-uppercase fw-bold">Min</label>
										<input type="number" step="0.01" class="form-control" name="male_min" id="setup-male-min">
									</div>
									<div class="col-6">
										<label class="fs-10 text-uppercase fw-bold">Max</label>
										<input type="number" step="0.01" class="form-control" name="male_max" id="setup-male-max">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="box box-outline-info shadow-none mb-0">
							<div class="box-header with-border p-2"><h6 class="mb-0">Female Parameters</h6></div>
							<div class="box-body p-3">
								<div class="form-group mb-2">
									<label class="form-label fs-12">Reference Text</label>
									<textarea rows="2" class="form-control" name="female_reference" id="setup-female-ref" placeholder="e.g. 60 - 100 mg/dl"></textarea>
								</div>
								<div class="row">
									<div class="col-6">
										<label class="fs-10 text-uppercase fw-bold">Min</label>
										<input type="number" step="0.01" class="form-control" name="female_min" id="setup-female-min">
									</div>
									<div class="col-6">
										<label class="fs-10 text-uppercase fw-bold">Max</label>
										<input type="number" step="0.01" class="form-control" name="female_max" id="setup-female-max">
									</div>
								</div>
							</div>
						</div>
					</div>
                    <div class="col-md-4">
						<div class="box box-outline-success shadow-none mb-0">
							<div class="box-header with-border p-2"><h6 class="mb-0">General Bio-Ref</h6></div>
							<div class="box-body p-3">
								<div class="form-group mb-0">
									<label class="form-label fs-12">Common Biological Reference</label>
									<textarea rows="5" class="form-control" name="biological_reference" id="setup-bio-ref" placeholder="Enter general biological reference interval for all genders..."></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary float-end" id="btn-save-params">Update Clinical Data</button>
		  </div>
		</div>
	  </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
	  $(document).ready(function() {
		  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

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
			  $('#setup-is-immuno').val($(this).data('is_immuno'));
			  $('#setup-bio-ref').val($(this).data('bio_ref'));
		  });

		  $('#btn-save-params').click(function() {
              let btn = $(this);
              btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');
			  $.post("{{ route('test-parameters.store') }}", $('#form-setup-params').serialize(), function(response) {
				  alert(response.success);
				  location.reload();
			  }).fail(function() {
                  alert("Error updating parameters.");
                  btn.prop('disabled', false).text('Update Clinical Data');
              });
		  });
	  });
  </script>
@endsection
