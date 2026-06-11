@extends('layouts.app')
@section('title', ' | Master Data Management')
@section('page-title', 'Master Data Management')

@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-database"></i></div>
        <div>
            <div>Master Data Management</div>
            <div style="font-size:13px; font-weight:400; color:var(--text-muted); margin-top:2px;">Manage laboratory measurement units, result templates, reference range templates, and flag templates</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Units Section -->
    <div class="col-xl-6 col-12">
        <div class="aw-card">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-flask" style="color:var(--primary);"></i> Laboratory Units</div>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ count($units) }} units</span>
                    <div style="position:relative;">
                        <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px;"></i>
                        <input type="text" id="unit-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:7px 12px 7px 30px;font-size:12px;outline:none;width:160px;" placeholder="Search units..." autocomplete="off" name="name_1024">
                    </div>
                </div>
            </div>
            <div class="aw-card-body">
                <form id="form-add-unit" class="mb-4">
                    @csrf
                    <div style="display:flex; gap:10px;">
                        <input type="text" name="name" class="form-control-aw" placeholder="Add new unit (e.g. mg/dl)" required autocomplete="off" id="field_1025">
                        <button type="submit" class="btn-aw-primary" style="flex-shrink:0;"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </form>
                <div class="table-responsive-modern" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-modern" id="units-table">
                        <thead>
                            <tr>
                                <th>SL. NO</th>
                                <th>Unit Name</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($units as $i => $unit)
                            <tr>
                                <td data-label="SL. NO"><span class="badge-aw badge-blue">{{ $i + 1 }}</span></td>
                                <td data-label="Unit Name" style="font-weight:600;">{{ $unit->name }}</td>
                                <td data-label="Action" class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn-aw-primary btn-aw-sm btn-edit-unit" data-id="{{ $unit->id }}" data-name="{{ $unit->name }}" data-bs-toggle="modal" data-bs-target="#modal-edit-master" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn-aw-danger btn-aw-sm btn-delete-unit" data-id="{{ $unit->id }}" title="Delete">
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
        <div class="aw-card">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-list-check" style="color:#059669;"></i> Result Templates</div>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ count($templates) }} templates</span>
                    <div style="position:relative;">
                        <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px;"></i>
                        <input type="text" id="template-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:7px 12px 7px 30px;font-size:12px;outline:none;width:160px;" placeholder="Search templates..." autocomplete="off" name="name_1026">
                    </div>
                </div>
            </div>
            <div class="aw-card-body">
                <form id="form-add-template" class="mb-4">
                    @csrf
                    <div style="display:flex; gap:10px;">
                        <input type="text" name="name" class="form-control-aw" placeholder="Add new result (e.g. Positive)" required autocomplete="off" id="field_1027">
                        <button type="submit" class="btn-aw-primary" style="flex-shrink:0; background:#059669;"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </form>
                <div class="table-responsive-modern" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-modern" id="templates-table">
                        <thead>
                            <tr>
                                <th>SL. NO</th>
                                <th>Template Name</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $i => $template)
                            <tr>
                                <td data-label="SL. NO"><span class="badge-aw badge-green">{{ $i + 1 }}</span></td>
                                <td data-label="Template Name" style="font-weight:600; color:#059669;">{{ $template->name }}</td>
                                <td data-label="Action" class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn-aw-primary btn-aw-sm btn-edit-template" data-id="{{ $template->id }}" data-name="{{ $template->name }}" data-bs-toggle="modal" data-bs-target="#modal-edit-master" title="Edit" style="background:#059669;">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn-aw-danger btn-aw-sm btn-delete-template" data-id="{{ $template->id }}" title="Delete">
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

<div class="row g-4 mt-2">
    <!-- Reference Range Templates Section -->
    <div class="col-xl-6 col-12">
        <div class="aw-card">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-file-medical" style="color:#2563eb;"></i> Reference Range Templates</div>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ count($referenceTemplates) }} templates</span>
                    <div style="position:relative;">
                        <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px;"></i>
                        <input type="text" id="reference-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:7px 12px 7px 30px;font-size:12px;outline:none;width:160px;" placeholder="Search references..." autocomplete="off" name="name_1028">
                    </div>
                </div>
            </div>
            <div class="aw-card-body">
                <form id="form-add-reference" class="mb-4">
                    @csrf
                    <div style="display:flex; gap:10px;">
                        <input type="text" name="name" class="form-control-aw" placeholder="Add new reference (e.g. 70 - 110)" required autocomplete="off" id="field_1029">
                        <button type="submit" class="btn-aw-primary" style="flex-shrink:0; background:#2563eb;"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </form>
                <div class="table-responsive-modern" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-modern" id="references-table">
                        <thead>
                            <tr>
                                <th>SL. NO</th>
                                <th>Template Value</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referenceTemplates as $i => $ref)
                            <tr>
                                <td data-label="SL. NO"><span class="badge-aw text-primary" style="background:#e0f2fe;">{{ $i + 1 }}</span></td>
                                <td data-label="Template Value" style="font-weight:600; color:#2563eb;">{{ $ref->name }}</td>
                                <td data-label="Action" class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn-aw-primary btn-aw-sm btn-edit-reference" data-id="{{ $ref->id }}" data-name="{{ $ref->name }}" data-bs-toggle="modal" data-bs-target="#modal-edit-master" title="Edit" style="background:#2563eb;">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn-aw-danger btn-aw-sm btn-delete-reference" data-id="{{ $ref->id }}" title="Delete">
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

    <!-- Flag Templates Section -->
    <div class="col-xl-6 col-12">
        <div class="aw-card">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-flag" style="color:#d97706;"></i> Flag Templates</div>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ count($flagTemplates) }} flags</span>
                    <div style="position:relative;">
                        <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px;"></i>
                        <input type="text" id="flag-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:7px 12px 7px 30px;font-size:12px;outline:none;width:160px;" placeholder="Search flags..." autocomplete="off" name="name_1030">
                    </div>
                </div>
            </div>
            <div class="aw-card-body">
                <form id="form-add-flag" class="mb-4">
                    @csrf
                    <div style="display:flex; gap:10px;">
                        <input type="text" name="name" class="form-control-aw" placeholder="Add new flag (e.g. H, L, C)" required autocomplete="off" id="field_1031">
                        <button type="submit" class="btn-aw-primary" style="flex-shrink:0; background:#d97706;"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </form>
                <div class="table-responsive-modern" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-modern" id="flags-table">
                        <thead>
                            <tr>
                                <th>SL. NO</th>
                                <th>Flag Value</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($flagTemplates as $i => $flg)
                            <tr>
                                <td data-label="SL. NO"><span class="badge-aw text-warning" style="background:#fef3c7;">{{ $i + 1 }}</span></td>
                                <td data-label="Flag Value" style="font-weight:600; color:#d97706;">{{ $flg->name }}</td>
                                <td data-label="Action" class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn-aw-primary btn-aw-sm btn-edit-flag" data-id="{{ $flg->id }}" data-name="{{ $flg->name }}" data-bs-toggle="modal" data-bs-target="#modal-edit-master" title="Edit" style="background:#d97706;">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn-aw-danger btn-aw-sm btn-delete-flag" data-id="{{ $flg->id }}" title="Delete">
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

<!-- Universal Edit Modal -->
<div class="modal fade modal-aw" id="modal-edit-master" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-edit-master">
                    <input type="hidden" id="edit-id" name="name_1032">
                    <input type="hidden" id="edit-type" name="name_1033">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label-aw">Name / Value</label>
                        <input type="text" id="edit-name" name="name" class="form-control-aw" required autocomplete="off">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-update-master"><i class="fa fa-check"></i> Update</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {

        // Initialize DataTables for Units
        var unitsTable = $('#units-table').DataTable({
            dom: "<'row mb-2'<'col-12'l>>" +
                 "<'row'<'col-12'tr>>" +
                 "<'row mt-2'<'col-12'p>>",
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: false,
            language: {
                lengthMenu: "Show _MENU_",
                infoEmpty: "",
                emptyTable: "No units added yet.",
                paginate: {
                    previous: "<i class='fa fa-angle-left'></i>",
                    next: "<i class='fa fa-angle-right'></i>"
                }
            }
        });
        $('#unit-search').on('keyup', function() {
            unitsTable.search($(this).val()).draw();
        });

        // Initialize DataTables for Templates
        var templatesTable = $('#templates-table').DataTable({
            dom: "<'row mb-2'<'col-12'l>>" +
                 "<'row'<'col-12'tr>>" +
                 "<'row mt-2'<'col-12'p>>",
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: false,
            language: {
                lengthMenu: "Show _MENU_",
                infoEmpty: "",
                emptyTable: "No result templates added yet.",
                paginate: {
                    previous: "<i class='fa fa-angle-left'></i>",
                    next: "<i class='fa fa-angle-right'></i>"
                }
            }
        });
        $('#template-search').on('keyup', function() {
            templatesTable.search($(this).val()).draw();
        });

        // Initialize DataTables for References
        var referencesTable = $('#references-table').DataTable({
            dom: "<'row mb-2'<'col-12'l>>" +
                 "<'row'<'col-12'tr>>" +
                 "<'row mt-2'<'col-12'p>>",
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: false,
            language: {
                lengthMenu: "Show _MENU_",
                infoEmpty: "",
                emptyTable: "No reference templates added yet.",
                paginate: {
                    previous: "<i class='fa fa-angle-left'></i>",
                    next: "<i class='fa fa-angle-right'></i>"
                }
            }
        });
        $('#reference-search').on('keyup', function() {
            referencesTable.search($(this).val()).draw();
        });

        // Initialize DataTables for Flags
        var flagsTable = $('#flags-table').DataTable({
            dom: "<'row mb-2'<'col-12'l>>" +
                 "<'row'<'col-12'tr>>" +
                 "<'row mt-2'<'col-12'p>>",
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: false,
            language: {
                lengthMenu: "Show _MENU_",
                infoEmpty: "",
                emptyTable: "No flag templates added yet.",
                paginate: {
                    previous: "<i class='fa fa-angle-left'></i>",
                    next: "<i class='fa fa-angle-right'></i>"
                }
            }
        });
        $('#flag-search').on('keyup', function() {
            flagsTable.search($(this).val()).draw();
        });

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

        // ADD Reference Template
        $('#form-add-reference').submit(function(e) {
            e.preventDefault();
            $.post("{{ route('reference-templates.store') }}", $(this).serialize(), function() { location.reload(); });
        });

        // DELETE Reference Template
        $(document).on('click', '.btn-delete-reference', function() {
            if(confirm('Remove this reference template?')) {
                $.ajax({ url: "/reference-templates/" + $(this).data('id'), type: 'DELETE', success: function() { location.reload(); } });
            }
        });

        // ADD Flag Template
        $('#form-add-flag').submit(function(e) {
            e.preventDefault();
            $.post("{{ route('flag-templates.store') }}", $(this).serialize(), function() { location.reload(); });
        });

        // DELETE Flag Template
        $(document).on('click', '.btn-delete-flag', function() {
            if(confirm('Remove this flag template?')) {
                $.ajax({ url: "/flag-templates/" + $(this).data('id'), type: 'DELETE', success: function() { location.reload(); } });
            }
        });

        // POPULATE Edit Modal — Unit
        $(document).on('click', '.btn-edit-unit', function() {
            $('#edit-id').val($(this).data('id'));
            $('#edit-name').val($(this).data('name'));
            $('#edit-type').val('unit');
            $('.modal-title').html('<i class="fa fa-pen me-2"></i>Edit Laboratory Unit');
        });

        // POPULATE Edit Modal — Template
        $(document).on('click', '.btn-edit-template', function() {
            $('#edit-id').val($(this).data('id'));
            $('#edit-name').val($(this).data('name'));
            $('#edit-type').val('template');
            $('.modal-title').html('<i class="fa fa-pen me-2"></i>Edit Result Template');
        });

        // POPULATE Edit Modal — Reference Template
        $(document).on('click', '.btn-edit-reference', function() {
            $('#edit-id').val($(this).data('id'));
            $('#edit-name').val($(this).data('name'));
            $('#edit-type').val('reference-template');
            $('.modal-title').html('<i class="fa fa-pen me-2"></i>Edit Reference Template');
        });

        // POPULATE Edit Modal — Flag Template
        $(document).on('click', '.btn-edit-flag', function() {
            $('#edit-id').val($(this).data('id'));
            $('#edit-name').val($(this).data('name'));
            $('#edit-type').val('flag-template');
            $('.modal-title').html('<i class="fa fa-pen me-2"></i>Edit Flag Template');
        });

        // UPDATE Logic
        $('#btn-update-master').click(function() {
            let id = $('#edit-id').val();
            let type = $('#edit-type').val();
            let url;
            if (type === 'unit') {
                url = "/units/" + id;
            } else if (type === 'template') {
                url = "/result-templates/" + id;
            } else if (type === 'reference-template') {
                url = "/reference-templates/" + id;
            } else if (type === 'flag-template') {
                url = "/flag-templates/" + id;
            }
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
@endpush
@endsection



