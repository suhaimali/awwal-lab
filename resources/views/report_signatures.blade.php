@extends('layouts.app')
@section('title', ' | Report Signatures')
@section('page-title', 'Report Signatures')

@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-signature"></i></div>
        <div>
            <div>Report Signatures</div>
            <div style="font-size:13px; font-weight:400; color:var(--text-muted); margin-top:2px;">Manage doctor signatures for report PDF generation</div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius:10px; background:#d1fae5; color:#065f46; font-size:13.5px; font-weight:500;">
        <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius:10px; background:#fee2e2; color:#991b1b; font-size:13.5px; font-weight:500;">
        <i class="fa fa-exclamation-triangle me-2"></i> {{ $errors->first() }}
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Add Signature -->
    <div class="col-xl-4 col-12">
        <div class="aw-card">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-plus-circle" style="color:var(--primary);"></i> Add Signature</div>
            </div>
            <div class="aw-card-body">
                <form action="{{ route('report-signatures.store') }}" method="POST" enctype="multipart/form-data" id="form-add-signature">
                    @csrf
                    <div class="mb-3">
                        <label for="field_1144" class="form-label-aw">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control-aw" value="{{ old('name') }}" required placeholder="e.g. Dr. Jane Doe" autocomplete="off" id="field_1144">
                    </div>
                    <div class="mb-3">
                        <label for="field_1145" class="form-label-aw">Signature Image <span class="text-danger">*</span></label>
                        <input type="file" name="signature_image" class="form-control-aw" accept="image/png,image/jpeg" required autocomplete="off" id="field_1145">
                        <div style="font-size:11px; color:var(--text-muted); margin-top:5px;"><i class="fa fa-info-circle me-1"></i> Recommended format: Transparent PNG image</div>
                    </div>
                    <div class="mb-3">
                        <label for="field_1146" class="form-label-aw">PIN <span class="text-danger">*</span></label>
                        <input type="password" name="pin" class="form-control-aw" required minlength="4" maxlength="20" autocomplete="new-password" placeholder="4 digits or more" id="field_1146">
                        <div style="font-size:11px; color:var(--text-muted); margin-top:5px;"><i class="fa fa-info-circle me-1"></i> Secure PIN to authorize report generation</div>
                    </div>
                    <button type="submit" class="btn-aw-primary w-100 mt-2">
                        <i class="fa fa-save"></i> Save Signature
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Saved Signatures -->
    <div class="col-xl-8 col-12">
        <div class="aw-card">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-list" style="color:#059669;"></i> Saved Signatures</div>
            </div>
            <div class="aw-card-body p-0">
                <div class="table-responsive-modern">
                    <table class="table-modern" id="signatures-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Signature Preview</th>
                                <th class="text-end" style="width: 140px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($signatures as $signature)
                                <tr>
                                    <td data-label="Name" style="font-weight:600;">{{ $signature->name }}</td>
                                    <td data-label="Signature Preview">
                                        <div style="background:#f8fafc; border:1px dashed var(--border-color); border-radius:8px; display:inline-block; padding:6px; max-width:160px;">
                                            <img src="{{ route('report-signatures.image', $signature->id) }}" alt="{{ $signature->name }}" style="max-height: 48px; object-fit: contain; display:block;">
                                        </div>
                                    </td>
                                    <td data-label="Action" class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button"
                                                class="btn-aw-primary btn-aw-sm btn-edit-signature"
                                                data-id="{{ $signature->id }}"
                                                data-name="{{ $signature->name }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-edit-signature"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn-aw-danger btn-aw-sm btn-delete-signature" data-id="{{ $signature->id }}" title="Delete">
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

<!-- Edit Signature Modal -->
<div class="modal fade modal-aw" id="modal-edit-signature" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-signature" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-edit me-2"></i>Edit Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-signature-name" class="form-label-aw">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control-aw" name="name" id="edit-signature-name" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="field_1147" class="form-label-aw">Replace Image</label>
                        <input type="file" class="form-control-aw" name="signature_image" accept="image/png,image/jpeg" autocomplete="off" id="field_1147">
                        <div style="font-size:11px; color:var(--text-muted); margin-top:5px;"><i class="fa fa-info-circle me-1"></i> Leave blank to keep current signature image</div>
                    </div>
                    <div class="mb-3">
                        <label for="field_1148" class="form-label-aw">New PIN</label>
                        <input type="password" class="form-control-aw" name="pin" minlength="4" maxlength="20" autocomplete="new-password" placeholder="PIN" id="field_1148">
                        <div style="font-size:11px; color:var(--text-muted); margin-top:5px;"><i class="fa fa-info-circle me-1"></i> Leave blank to keep current PIN</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-aw-primary">Update Signature</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#signatures-table').DataTable({
            dom: "<'row mb-3'<'col-sm-12 col-md-6'l>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            ordering: false,
            language: {
                lengthMenu: "Show _MENU_ records",
                info: "Showing _START_ to _END_ of _TOTAL_ signatures",
                infoEmpty: "Showing 0 to 0 of 0 signatures",
                infoFiltered: "(filtered from _MAX_ total signatures)",
                emptyTable: "No report signatures added yet.",
                paginate: {
                    previous: "<i class='fa fa-angle-left'></i>",
                    next: "<i class='fa fa-angle-right'></i>"
                }
            }
        });

        $(document).on('click', '.btn-edit-signature', function() {
            $('#edit-signature-name').val($(this).data('name'));
            $('#form-edit-signature').attr('action', '/report-signatures/' + $(this).data('id'));
        });

        $(document).on('click', '.btn-delete-signature', function() {
            if (!confirm('Delete this signature? Reports using it will no longer show it.')) return;

            var $btn = $(this);
            var originalHtml = $btn.html();
            $btn.html('<i class="fa fa-spinner fa-spin"></i>');
            $btn.prop('disabled', true);

            setTimeout(function() {
                $.ajax({
                    url: '/report-signatures/' + $btn.data('id'),
                    type: 'DELETE',
                    success: function() { location.reload(); },
                    error: function(xhr) {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        alert(xhr.responseJSON?.message || 'Could not delete signature.');
                    }
                });
            }, 2000);
        });

        $('#form-add-signature, #form-edit-signature').on('submit', function(e) {
            var $form = $(this);
            var $btn = $form.find('button[type="submit"]');
            
            if ($btn.data('is-submitting')) {
                return;
            }
            
            e.preventDefault();
            $btn.data('is-submitting', true);
            var originalText = $btn.html();
            $btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            $btn.prop('disabled', true);
            
            setTimeout(function() {
                $form[0].submit();
            }, 2000);
        });
    });
</script>
@endpush
@endsection

