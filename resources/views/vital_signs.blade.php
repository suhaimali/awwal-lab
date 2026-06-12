@extends('layouts.app')
@section('title', ' | Vital Signs')
@section('page-title', 'Vital Signs')
@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-heartbeat"></i></div>
        <div>
            <div>Patient Vital Signs</div>
            <div style="font-size:13px;font-weight:400;color:var(--text-muted);margin-top:2px;">Record and track clinical measurements</div>
        </div>
    </div>
    <button type="button" class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-vitals">
        <i class="fa fa-plus"></i> Record Vital Signs
    </button>
</div>

<style>
    .cat-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        color: #ef4444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.15);
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

    .btn-icon-circle.delete:hover {
        color: #ef4444;
        border-color: #fecaca;
        background: #fef2f2;
    }

    .modal-aw .form-label {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .modal-aw .form-control, 
    .modal-aw .form-select {
        border: 1.5px solid #cbd5e1;
        border-radius: 9px;
        padding: 9px 12px;
        font-size: 13.5px;
        color: #1e293b;
        background-color: #ffffff;
        outline: none;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .modal-aw .form-control:focus, 
    .modal-aw .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1) !important;
        background-color: #ffffff;
    }

    .bmi-badge-preview {
        font-size: 12px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
    }

</style>

<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-heartbeat" style="color:var(--primary);"></i> Vitals Directory</div>
        <div class="d-flex align-items-center gap-3">
            <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ $vitalSigns->count() }} total records</span>
            <div style="position:relative;">
                <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                <input type="text" id="vitals-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search vitals..." autocomplete="off">
            </div>
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">
            <table class="table-modern" id="vitals-table">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Temperature</th>
                                <th>Pulse</th>
                                <th>Resp. Rate</th>
                                <th>Blood Pressure</th>
                                <th>SpO2</th>
                                <th>Weight & Height</th>
                                <th>BMI</th>
                                <th>Recorded Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vitalSigns as $vital)
                                <tr>
                                    <td style="padding:14px 20px;" data-label="Patient">
                                        <div class="d-flex align-items-center">
                                            <div class="cat-icon-box me-3">
                                                <i class="fa fa-heartbeat"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight:600; color:#1e293b;">
                                                    {{ $vital->patient->first_name ?? '' }} {{ $vital->patient->last_name ?? '' }}
                                                </div>
                                                <div style="font-size:11px; color:var(--text-muted);">
                                                    ID: {{ $vital->patient->patient_id ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:14px 12px;" data-label="Temperature">
                                        @if($vital->temperature)
                                            <span style="font-weight:500; color:#334155;">{{ $vital->temperature }} °{{ $vital->temp_unit ?? 'F' }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 12px;" data-label="Pulse">
                                        @if($vital->pulse)
                                            <span style="font-weight:500; color:#334155;">{{ $vital->pulse }} bpm</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 12px;" data-label="Resp. Rate">
                                        @if($vital->respiratory_rate)
                                            <span style="font-weight:500; color:#334155;">{{ $vital->respiratory_rate }} /min</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 12px;" data-label="Blood Pressure">
                                        @if($vital->blood_pressure)
                                            <span style="font-weight:500; color:#334155;">{{ $vital->blood_pressure }} mmHg</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 12px;" data-label="SpO2">
                                        @if($vital->spo2)
                                            @if($vital->spo2 < 95)
                                                <span class="badge bg-danger rounded-pill px-2.5 py-1">{{ $vital->spo2 }}% (Low)</span>
                                            @else
                                                <span class="badge bg-success rounded-pill px-2.5 py-1">{{ $vital->spo2 }}%</span>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 12px;" data-label="Weight & Height">
                                        @if($vital->weight || $vital->height)
                                            <span style="font-weight:500; color:#334155;">
                                                {{ $vital->weight ?? '—' }} kg / {{ $vital->height ?? '—' }} cm
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 12px;" data-label="BMI">
                                        @if($vital->bmi)
                                            @php
                                                $bmi = $vital->bmi;
                                                $colorClass = 'bg-secondary';
                                                $status = 'Normal';
                                                if ($bmi < 18.5) {
                                                    $colorClass = 'bg-info';
                                                    $status = 'Underweight';
                                                } elseif ($bmi >= 18.5 && $bmi < 25) {
                                                    $colorClass = 'bg-success';
                                                    $status = 'Normal';
                                                } elseif ($bmi >= 25 && $bmi < 30) {
                                                    $colorClass = 'bg-warning';
                                                    $status = 'Overweight';
                                                } else {
                                                    $colorClass = 'bg-danger';
                                                    $status = 'Obese';
                                                }
                                            @endphp
                                            <span class="badge {{ $colorClass }} px-2 py-1" style="font-size:11.5px; font-weight:600;">
                                                {{ $bmi }} ({{ $status }})
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 12px;" data-label="Recorded Date">
                                        <div style="font-size:13px; color:#334155; font-weight:500;">
                                            {{ $vital->created_at->format('d-M-Y') }}
                                        </div>
                                        <div style="font-size:11px; color:var(--text-muted);">
                                            {{ $vital->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td style="padding:14px 20px;" data-label="Actions" class="text-end">
                                        <div class="action-btn-group">
                                            <button type="button" class="btn-icon-circle edit btn-edit-vitals" data-id="{{ $vital->id }}" title="Edit Entry">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button type="button" class="btn-icon-circle delete btn-delete-vitals" data-id="{{ $vital->id }}" title="Delete Entry">
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

<!-- Add Modal -->
<div class="modal fade modal-aw" id="modal-add-vitals" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-heartbeat me-2"></i>Record Patient Vitals</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="form-add-vitals" autocomplete="off">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="add-patient-id" class="form-label">Select Patient</label>
                                <select class="form-select" name="patient_id" id="add-patient-id" required>
                                    <option value="">-- Select Patient --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} (ID: {{ $patient->patient_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add-temp" class="form-label">Temperature</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" class="form-control" name="temperature" id="add-temp" placeholder="e.g. 98.6">
                                    <select class="form-select" name="temp_unit" id="add-temp-unit" style="max-width: 80px; font-weight: 500;">
                                        <option value="F">°F</option>
                                        <option value="C">°C</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add-pulse" class="form-label">Pulse Rate (bpm)</label>
                                <input type="number" class="form-control" name="pulse" id="add-pulse" placeholder="e.g. 72">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add-rr" class="form-label">Resp. Rate (/min)</label>
                                <input type="number" class="form-control" name="respiratory_rate" id="add-rr" placeholder="e.g. 16">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add-bp" class="form-label">Blood Pressure (mmHg)</label>
                                <input type="text" class="form-control" name="blood_pressure" id="add-bp" placeholder="e.g. 120/80">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="add-spo2" class="form-label">SpO2 (%)</label>
                                <input type="number" class="form-control" name="spo2" id="add-spo2" placeholder="e.g. 98">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Auto BMI Preview</label>
                                <div class="d-flex align-items-center" style="height:42px;">
                                    <span class="badge bg-secondary bmi-badge-preview" id="add-bmi-preview">—</span>
                                    <input type="hidden" name="bmi" id="add-bmi-val">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add-weight" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" class="form-control bmi-trigger" name="weight" id="add-weight" placeholder="e.g. 70">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add-height" class="form-label">Height (cm)</label>
                                <input type="number" step="0.01" class="form-control bmi-trigger" name="height" id="add-height" placeholder="e.g. 170">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="add-notes" class="form-label">Clinical Notes / Symptoms</label>
                        <textarea class="form-control" name="notes" id="add-notes" rows="3" placeholder="Enter clinical observations, symptoms..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" style="font-weight:600; font-size:14px; border:1px solid #e2e8f0;" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-3 px-4 py-2" id="btn-save-vitals" style="font-weight:600; font-size:14px; background:var(--primary); border:none;">
                    <i class="fa fa-check me-1"></i> Save Record
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade modal-aw" id="modal-edit-vitals" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-heartbeat me-2"></i>Edit Vital Signs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="form-edit-vitals" autocomplete="off">
                    @csrf
                    <input type="hidden" id="edit-vital-id">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit-patient-id" class="form-label">Patient</label>
                                <select class="form-select" name="patient_id" id="edit-patient-id" required>
                                    <option value="">-- Select Patient --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} (ID: {{ $patient->patient_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-temp" class="form-label">Temperature</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" class="form-control" name="temperature" id="edit-temp" placeholder="e.g. 98.6">
                                    <select class="form-select" name="temp_unit" id="edit-temp-unit" style="max-width: 80px; font-weight: 500;">
                                        <option value="F">°F</option>
                                        <option value="C">°C</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-pulse" class="form-label">Pulse Rate (bpm)</label>
                                <input type="number" class="form-control" name="pulse" id="edit-pulse" placeholder="e.g. 72">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-rr" class="form-label">Resp. Rate (/min)</label>
                                <input type="number" class="form-control" name="respiratory_rate" id="edit-rr" placeholder="e.g. 16">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-bp" class="form-label">Blood Pressure (mmHg)</label>
                                <input type="text" class="form-control" name="blood_pressure" id="edit-bp" placeholder="e.g. 120/80">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit-spo2" class="form-label">SpO2 (%)</label>
                                <input type="number" class="form-control" name="spo2" id="edit-spo2" placeholder="e.g. 98">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Auto BMI Preview</label>
                                <div class="d-flex align-items-center" style="height:42px;">
                                    <span class="badge bg-secondary bmi-badge-preview" id="edit-bmi-preview">—</span>
                                    <input type="hidden" name="bmi" id="edit-bmi-val">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-weight" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" class="form-control bmi-edit-trigger" name="weight" id="edit-weight" placeholder="e.g. 70">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit-height" class="form-label">Height (cm)</label>
                                <input type="number" step="0.01" class="form-control bmi-edit-trigger" name="height" id="edit-height" placeholder="e.g. 170">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit-notes" class="form-label">Clinical Notes / Symptoms</label>
                        <textarea class="form-control" name="notes" id="edit-notes" rows="3" placeholder="Enter clinical observations, symptoms..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" style="font-weight:600; font-size:14px; border:1px solid #e2e8f0;" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-3 px-4 py-2" id="btn-update-vitals" style="font-weight:600; font-size:14px; background:var(--primary); border:none;">
                    <i class="fa fa-check me-1"></i> Update Changes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // --- CSRF token setup ---
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Initialize DataTables
        var table = $('#vitals-table').DataTable({
            dom: "<'row mb-3'<'col-sm-12 col-md-6'l>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            ordering: false,
            language: {
                lengthMenu: "Show _MENU_ records",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                emptyTable: "No vital signs records found.",
                paginate: {
                    previous: "<i class='fa fa-angle-left'></i>",
                    next: "<i class='fa fa-angle-right'></i>"
                }
            }
        });

        // Global search filtering
        $("#vitals-search").on("keyup", function() {
            table.search($(this).val()).draw();
        });

        // Dynamic BMI calculator logic
        function updateBMIPreview(wId, hId, pId, valId) {
            let weight = parseFloat($(wId).val());
            let height = parseFloat($(hId).val());
            if (weight > 0 && height > 0) {
                let heightM = height / 100;
                let bmi = weight / (heightM * heightM);
                let val = bmi.toFixed(1);
                
                $(pId).text(val);
                $(valId).val(val);

                // Update badge styling based on standard intervals
                $(pId).removeClass('bg-secondary bg-info bg-success bg-warning bg-danger');
                if (bmi < 18.5) {
                    $(pId).addClass('bg-info'); // Underweight
                } else if (bmi >= 18.5 && bmi < 25) {
                    $(pId).addClass('bg-success'); // Normal
                } else if (bmi >= 25 && bmi < 30) {
                    $(pId).addClass('bg-warning'); // Overweight
                } else {
                    $(pId).addClass('bg-danger'); // Obese
                }
            } else {
                $(pId).text('—').removeClass('bg-info bg-success bg-warning bg-danger').addClass('bg-secondary');
                $(valId).val('');
            }
        }

        // Bind BMI listeners for Add Modal
        $('.bmi-trigger').on('input', function() {
            updateBMIPreview('#add-weight', '#add-height', '#add-bmi-preview', '#add-bmi-val');
        });

        // Bind BMI listeners for Edit Modal
        $('.bmi-edit-trigger').on('input', function() {
            updateBMIPreview('#edit-weight', '#edit-height', '#edit-bmi-preview', '#edit-bmi-val');
        });

        // Save Records
        $('#btn-save-vitals').click(function() {
            let btn = $(this);
            if(btn.hasClass('disabled')) return;
            btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...').addClass('disabled');

            $.ajax({
                url: "{{ route('vital-signs.store') }}",
                type: 'POST',
                data: $('#form-add-vitals').serialize(),
                success: function(response) {
                    Swal.fire({
                        title: 'Success',
                        text: response.success,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    btn.html('<i class="fa fa-check"></i> Save Record').removeClass('disabled');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorStr = '';
                        $.each(errors, function(key, val) {
                            errorStr += val[0] + '<br>';
                        });
                        Swal.fire('Validation Error', errorStr, 'error');
                    } else {
                        Swal.fire('Error', 'Failed to save vital signs record.', 'error');
                    }
                }
            });
        });

        // Edit Mode (Populate Form)
        $(document).on('click', '.btn-edit-vitals', function() {
            let id = $(this).data('id');
            $.get("/vital-signs/" + id, function(data) {
                $('#edit-vital-id').val(data.id);
                $('#edit-patient-id').val(data.patient_id);
                $('#edit-temp').val(data.temperature);
                $('#edit-temp-unit').val(data.temp_unit || 'F');
                $('#edit-pulse').val(data.pulse);
                $('#edit-rr').val(data.respiratory_rate);
                $('#edit-bp').val(data.blood_pressure);
                $('#edit-spo2').val(data.spo2);
                $('#edit-weight').val(data.weight);
                $('#edit-height').val(data.height);
                $('#edit-notes').val(data.notes);

                // Trigger BMI calculation preview
                updateBMIPreview('#edit-weight', '#edit-height', '#edit-bmi-preview', '#edit-bmi-val');

                $('#modal-edit-vitals').modal('show');
            }).fail(function() {
                Swal.fire('Error', 'Failed to fetch vital signs record details.', 'error');
            });
        });

        // Update Records
        $('#btn-update-vitals').click(function() {
            let btn = $(this);
            if(btn.hasClass('disabled')) return;
            let id = $('#edit-vital-id').val();
            btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...').addClass('disabled');

            $.ajax({
                url: "/vital-signs/update/" + id,
                type: 'POST',
                data: $('#form-edit-vitals').serialize(),
                success: function(response) {
                    Swal.fire({
                        title: 'Success',
                        text: response.success,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    btn.html('<i class="fa fa-check"></i> Update Changes').removeClass('disabled');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorStr = '';
                        $.each(errors, function(key, val) {
                            errorStr += val[0] + '<br>';
                        });
                        Swal.fire('Validation Error', errorStr, 'error');
                    } else {
                        Swal.fire('Error', 'Failed to update vital signs record.', 'error');
                    }
                }
            });
        });

        // Delete Records
        $(document).on('click', '.btn-delete-vitals', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/vital-signs/" + id,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.success,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to delete vital signs record.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
