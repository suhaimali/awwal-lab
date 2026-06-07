@extends('layouts.app')
@section('title', ' | Deleted Reports Trash')
@section('page-title', 'Deleted Reports Trash')

@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon" style="background: #fee2e2; color: #dc2626;"><i class="fa fa-trash-alt"></i></div>
        <div>
            <div>Deleted Reports Trash</div>
            <div style="font-size:13px; font-weight:400; color:var(--text-muted); margin-top:2px;">Restore deleted reports or permanently remove them from the database</div>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('reports') }}" class="btn-aw-primary">
            <i class="fa fa-arrow-left"></i> Back to Reports
        </a>
    </div>
</div>

<style>


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

    .btn-icon-circle.restore:hover {
        color: #059669;
        border-color: #a7f3d0;
        background: #ecfdf5;
    }

    .btn-icon-circle.delete:hover {
        color: #ef4444;
        border-color: #fecaca;
        background: #fef2f2;
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
    }
</style>

<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-trash-alt" style="color:#dc2626;"></i> Trash Items</div>
        <div style="position:relative;">
            <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
            <input type="text" id="trash-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search trash..." autocomplete="off">
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">
            
                <table class="table-modern" id="trash-table">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Patient Name</th>
                            <th>Doctor Name</th>
                            <th>Deleted At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td data-label="SL No">
                                <span class="badge-aw" style="background:#fee2e2;color:#dc2626;font-family:monospace;font-size:12px;padding:6px 10px;border-radius:6px;border:1px solid #fca5a5;">
                                    #{{ $report->id }}
                                </span>
                            </td>
                            <td data-label="Patient Name" style="font-weight:600; color:#1e293b;">
                                {{ $report->patient ? $report->patient->first_name . ' ' . $report->patient->last_name : 'N/A' }}
                            </td>
                            <td data-label="Doctor Name" style="color:#64748b;">
                                {{ $report->doctor_name }}
                            </td>
                            <td data-label="Deleted At" style="color:#64748b;">
                                {{ $report->deleted_at ? $report->deleted_at->format('d-M-Y h:i A') : 'N/A' }}
                            </td>
                            <td data-label="Action" class="text-end">
                                <div class="action-btn-group">
                                    <button class="btn-icon-circle restore btn-restore" data-id="{{ $report->id }}" title="Restore Report">
                                        <i class="fa fa-undo"></i>
                                    </button>
                                    <button class="btn-icon-circle delete btn-force-delete" data-id="{{ $report->id }}" title="Permanently Delete">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding:40px; color:var(--text-muted);">
                                <i class="fa fa-trash-alt mb-3" style="font-size:32px; color:#cbd5e1; display:block;"></i>
                                No deleted reports in the trash.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Search functionality
        $("#trash-search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#trash-table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Restore Report
        $(document).on('click', '.btn-restore', function() {
            let id = $(this).data('id');
            let row = $(this).closest('tr');
            if(confirm('Are you sure you want to restore this report?')) {
                $.ajax({
                    url: "/reports/" + id + "/restore",
                    type: 'POST',
                    success: function(response) {
                        alert(response.success);
                        row.fadeOut(300, function() {
                            row.remove();
                            if ($("#trash-table tbody tr").length === 0) {
                                $("#trash-table tbody").html(`
                                    <tr>
                                        <td colspan="5" class="text-center" style="padding:40px; color:var(--text-muted);">
                                            <i class="fa fa-trash-alt mb-3" style="font-size:32px; color:#cbd5e1; display:block;"></i>
                                            No deleted reports in the trash.
                                        </td>
                                    </tr>
                                `);
                            }
                        });
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Error restoring report.');
                    }
                });
            }
        });

        // Permanently Delete Report
        $(document).on('click', '.btn-force-delete', function() {
            let id = $(this).data('id');
            let row = $(this).closest('tr');
            if(confirm('WARNING: This will permanently delete this report. This action CANNOT be undone. Are you sure?')) {
                $.ajax({
                    url: "/reports/" + id + "/force-delete",
                    type: 'DELETE',
                    success: function(response) {
                        alert(response.success);
                        row.fadeOut(300, function() {
                            row.remove();
                            if ($("#trash-table tbody tr").length === 0) {
                                $("#trash-table tbody").html(`
                                    <tr>
                                        <td colspan="5" class="text-center" style="padding:40px; color:var(--text-muted);">
                                            <i class="fa fa-trash-alt mb-3" style="font-size:32px; color:#cbd5e1; display:block;"></i>
                                            No deleted reports in the trash.
                                        </td>
                                    </tr>
                                `);
                            }
                        });
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Error deleting report.');
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection



