@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Deleted Reports</h4>
                </div>
                <a href="{{ route('reports') }}" class="btn btn-light btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back to Reports
                </a>
            </div>
        </div>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Deleted On</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>#{{ $report->id }}</td>
                                        <td>{{ $report->patient->first_name ?? '' }} {{ $report->patient->last_name ?? '' }}</td>
                                        <td>{{ $report->doctor_name }}</td>
                                        <td>{{ optional($report->deleted_at)->format('d-M-Y h:i A') }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-success-light btn-sm btn-restore-report" data-id="{{ $report->id }}">
                                                <i class="fa fa-undo me-1"></i> Restore
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-40 text-fade">No deleted reports found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $('.btn-restore-report').click(function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Restoring');
            $.post('/reports/' + btn.data('id') + '/restore', function(response) {
                alert(response.success);
                location.reload();
            }).fail(function() {
                alert('Failed to restore report.');
                btn.prop('disabled', false).html('<i class="fa fa-undo me-1"></i> Restore');
            });
        });
    });
</script>
@endsection
