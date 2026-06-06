@extends('layouts.app')
@section('content')
 <div class="content-wrapper">
	  <div class="container-full">
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Report Signatures</h4>
				</div>
			</div>
		</div>

		<section class="content">
			@if(session('success'))
				<div class="alert alert-success">{{ session('success') }}</div>
			@endif
			@if($errors->any())
				<div class="alert alert-danger">
					{{ $errors->first() }}
				</div>
			@endif

			<div class="row">
				<div class="col-xl-4 col-12">
					<div class="box">
						<div class="box-header with-border">
							<h4 class="box-title text-primary"><i class="fa fa-plus-circle me-2"></i>Add Signature</h4>
						</div>
						<div class="box-body">
							<form action="{{ route('report-signatures.store') }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label class="form-label">Name <span class="text-danger">*</span></label>
									<input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Medi Technician">
								</div>
								<div class="form-group">
									<label class="form-label">Signature Image <span class="text-danger">*</span></label>
									<input type="file" name="signature_image" class="form-control" accept="image/png,image/jpeg" required>
								</div>
								<div class="form-group">
									<label class="form-label">PIN <span class="text-danger">*</span></label>
									<input type="password" name="pin" class="form-control" required minlength="4" maxlength="20" autocomplete="new-password" placeholder="4 digits or more">
								</div>
								<button type="submit" class="btn btn-primary w-100">
									<i class="fa fa-save me-1"></i> Save Signature
								</button>
							</form>
						</div>
					</div>
				</div>

				<div class="col-xl-8 col-12">
					<div class="box">
						<div class="box-header with-border">
							<h4 class="box-title text-success"><i class="fa fa-pencil-square-o me-2"></i>Saved Signatures</h4>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover mb-0">
									<thead class="bg-light">
										<tr>
											<th>Name</th>
											<th>Signature</th>
											<th class="text-end" style="width: 140px;">Action</th>
										</tr>
									</thead>
									<tbody>
										@forelse($signatures as $signature)
											<tr>
												<td class="fw-600">{{ $signature->name }}</td>
												<td>
													<img src="{{ route('report-signatures.image', $signature->id) }}" alt="{{ $signature->name }}" style="max-width: 150px; max-height: 56px; object-fit: contain;">
												</td>
												<td class="text-end">
													<div class="d-flex justify-content-end gap-2">
														<button type="button"
															class="btn btn-info-light btn-sm btn-edit-signature"
															data-id="{{ $signature->id }}"
															data-name="{{ $signature->name }}"
															data-bs-toggle="modal"
															data-bs-target="#modal-edit-signature"
															title="Edit">
															<i class="fa fa-edit"></i>
														</button>
														<button type="button" class="btn btn-danger-light btn-sm btn-delete-signature" data-id="{{ $signature->id }}" title="Delete">
															<i class="fa fa-trash"></i>
														</button>
													</div>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="3" class="text-center py-40 text-fade">
													No report signatures added yet.
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

  <div class="modal center-modal fade" id="modal-edit-signature" tabindex="-1">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <form id="form-edit-signature" method="POST" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			<div class="modal-header">
				<h5 class="modal-title">Edit Signature</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-label">Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="name" id="edit-signature-name" required>
				</div>
				<div class="form-group">
					<label class="form-label">Replace Image</label>
					<input type="file" class="form-control" name="signature_image" accept="image/png,image/jpeg">
				</div>
				<div class="form-group">
					<label class="form-label">New PIN</label>
					<input type="password" class="form-control" name="pin" minlength="4" maxlength="20" autocomplete="new-password" placeholder="Leave blank to keep current PIN">
				</div>
			</div>
			<div class="modal-footer modal-footer-uniform">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		  </form>
		</div>
	  </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
	  $(document).ready(function() {
		  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

		  $(document).on('click', '.btn-edit-signature', function() {
			  $('#edit-signature-name').val($(this).data('name'));
			  $('#form-edit-signature').attr('action', '/report-signatures/' + $(this).data('id'));
		  });

		  $(document).on('click', '.btn-delete-signature', function() {
			  if (!confirm('Delete this signature? Reports using it will no longer show it.')) return;

			  $.ajax({
				  url: '/report-signatures/' + $(this).data('id'),
				  type: 'DELETE',
				  success: function() { location.reload(); },
				  error: function(xhr) {
					  alert(xhr.responseJSON?.message || 'Could not delete signature.');
				  }
			  });
		  });
	  });
  </script>
@endsection
