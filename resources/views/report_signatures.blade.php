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
                <form action="{{ route('report-signatures.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-aw">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control-aw" value="{{ old('name') }}" required placeholder="e.g. Dr. Jane Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-aw">Signature Image <span class="text-danger">*</span></label>
                        <input type="file" name="signature_image" class="form-control-aw" accept="image/png,image/jpeg" required>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:5px;"><i class="fa fa-info-circle me-1"></i> Recommended format: Transparent PNG image</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-aw">PIN <span class="text-danger">*</span></label>
                        <input type="password" name="pin" class="form-control-aw" required minlength="4" maxlength="20" autocomplete="new-password" placeholder="4 digits or more">
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
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Signature Preview</th>
                                <th class="text-end" style="width: 140px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($signatures as $signature)
                                <tr>
                                    <td style="font-weight:600;">{{ $signature->name }}</td>
                                    <td>
                                        <div style="background:#f8fafc; border:1px dashed var(--border-color); border-radius:8px; display:inline-block; padding:6px; max-width:160px;">
                                            <img src="{{ route('report-signatures.image', $signature->id) }}" alt="{{ $signature->name }}" style="max-height: 48px; object-fit: contain; display:block;">
                                        </div>
                                    </td>
                                    <td class="text-end">
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
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center" style="padding:48px; color:var(--text-muted);">
                                        <i class="fa fa-folder-open" style="font-size:40px; display:block; margin-bottom:12px; opacity:0.4;"></i>
                                        <span style="font-size:15px;">No report signatures added yet.</span>
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
                        <label class="form-label-aw">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control-aw" name="name" id="edit-signature-name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-aw">Replace Image</label>
                        <input type="file" class="form-control-aw" name="signature_image" accept="image/png,image/jpeg">
                        <div style="font-size:11px; color:var(--text-muted); margin-top:5px;"><i class="fa fa-info-circle me-1"></i> Leave blank to keep current signature image</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-aw">New PIN</label>
                        <input type="password" class="form-control-aw" name="pin" minlength="4" maxlength="20" autocomplete="new-password" placeholder="PIN">
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
@endpush
@endsection

