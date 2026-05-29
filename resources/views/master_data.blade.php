@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Master Data Management</h4>

				</div>
			</div>
		</div>

		<section class="content">
			<div class="row">
				<!-- Units Section -->
				<div class="col-xl-6 col-12">
					<div class="box">
						<div class="box-header with-border">
							<h4 class="box-title text-primary"><i class="fa fa-flask me-2"></i>Laboratory Units</h4>
						</div>
						<div class="box-body">
							<form id="form-add-unit" class="mb-4">
								@csrf
								<div class="input-group">
									<input type="text" name="name" class="form-control" placeholder="Add new unit (e.g. mg/dl)" required>
									<button type="submit" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Add</button>
								</div>
							</form>
							<div class="table-responsive" style="max-height: 500px;">
								<table class="table table-hover mb-0">
									<thead class="bg-light">
										<tr>
											<th>Unit Name</th>
											<th class="text-end" style="width: 120px;">Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($units as $unit)
										<tr>
											<td class="fw-600">{{ $unit->name }}</td>
											<td class="text-end">
												<div class="d-flex justify-content-end gap-2">
													<button class="btn btn-info-light btn-sm btn-edit-unit" data-id="{{ $unit->id }}" data-name="{{ $unit->name }}" data-bs-toggle="modal" data-bs-target="#modal-edit-master">
														<i class="fa fa-edit"></i>
													</button>
													<button class="btn btn-danger-light btn-sm btn-delete-unit" data-id="{{ $unit->id }}">
														<i class="fa fa-trash"></i>
													</button>
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

				<!-- Result Templates Section -->
				<div class="col-xl-6 col-12">
					<div class="box">
						<div class="box-header with-border">
							<h4 class="box-title text-success"><i class="fa fa-list-alt me-2"></i>Result Templates</h4>
						</div>
						<div class="box-body">
							<form id="form-add-template" class="mb-4">
								@csrf
								<div class="input-group">
									<input type="text" name="name" class="form-control" placeholder="Add new result (e.g. Positive)" required>
									<button type="submit" class="btn btn-success"><i class="fa fa-plus me-1"></i> Add</button>
								</div>
							</form>
							<div class="table-responsive" style="max-height: 500px;">
								<table class="table table-hover mb-0">
									<thead class="bg-light">
										<tr>
											<th>Template Name</th>
											<th class="text-end" style="width: 120px;">Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($templates as $template)
										<tr>
											<td class="fw-600 text-success">{{ $template->name }}</td>
											<td class="text-end">
												<div class="d-flex justify-content-end gap-2">
													<button class="btn btn-info-light btn-sm btn-edit-template" data-id="{{ $template->id }}" data-name="{{ $template->name }}" data-bs-toggle="modal" data-bs-target="#modal-edit-master">
														<i class="fa fa-edit"></i>
													</button>
													<button class="btn btn-danger-light btn-sm btn-delete-template" data-id="{{ $template->id }}">
														<i class="fa fa-trash"></i>
													</button>
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
			</div>
		</section>
	  </div>
  </div>

  <!-- Universal Edit Modal -->
  <div class="modal center-modal fade" id="modal-edit-master" tabindex="-1">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Edit Entry</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form id="form-edit-master">
				<input type="hidden" id="edit-id">
				<input type="hidden" id="edit-type"> <!-- 'unit' or 'template' -->
				<div class="form-group">
					<label class="form-label">Name / Value</label>
					<input type="text" id="edit-name" name="name" class="form-control" required>
				</div>
			</form>
		  </div>
		  <div class="modal-footer modal-footer-uniform text-end">
			<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" id="btn-update-master">Update Changes</button>
		  </div>
		</div>
	  </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
	  $(document).ready(function() {
		  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

		  // ADD Unit
		  $('#form-add-unit').submit(function(e) {
			  e.preventDefault();
			  $.post("{{ route('units.store') }}", $(this).serialize(), function() { location.reload(); });
		  });

		  // DELETE Unit
		  $(document).on('click', '.btn-delete-unit', function() {
			  if(confirm('Remove this unit?')) {
				  $.ajax({ url: "/units/" + $(this).data('id'), type: 'DELETE', success: function() { location.reload(); } });
			  }
		  });

		  // ADD Template
		  $('#form-add-template').submit(function(e) {
			  e.preventDefault();
			  $.post("{{ route('result-templates.store') }}", $(this).serialize(), function() { location.reload(); });
		  });

		  // DELETE Template
		  $(document).on('click', '.btn-delete-template', function() {
			  if(confirm('Remove this template?')) {
				  $.ajax({ url: "/result-templates/" + $(this).data('id'), type: 'DELETE', success: function() { location.reload(); } });
			  }
		  });

		  // POPULATE Edit Modal
		  $(document).on('click', '.btn-edit-unit', function() {
			  $('#edit-id').val($(this).data('id'));
			  $('#edit-name').val($(this).data('name'));
			  $('#edit-type').val('unit');
			  $('.modal-title').text('Edit Laboratory Unit');
		  });

		  $(document).on('click', '.btn-edit-template', function() {
			  $('#edit-id').val($(this).data('id'));
			  $('#edit-name').val($(this).data('name'));
			  $('#edit-type').val('template');
			  $('.modal-title').text('Edit Result Template');
		  });

		  // UPDATE Logic
		  $('#btn-update-master').click(function() {
			  let id = $('#edit-id').val();
			  let type = $('#edit-type').val();
			  let url = type === 'unit' ? "/units/" + id : "/result-templates/" + id;
			  
			  $.ajax({
				  url: url,
				  type: 'PUT',
				  data: $('#form-edit-master').serialize(),
				  success: function() { location.reload(); },
				  error: function() { alert("Error updating entry. Possibly a duplicate name."); }
			  });
		  });
	  });
  </script>
@endsection
