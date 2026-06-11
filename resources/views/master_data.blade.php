@extends('layouts.app')
@section('title', ' | Master Data Management')
@section('page-title', 'Master Data Management')

@section('content')

<style>
/* ── Master Data Page Styles ─────────────────────────────── */
.md-page-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}
.md-page-header .md-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: var(--primary, #4f46e5);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 18px;
    flex-shrink: 0;
}
.md-page-header h1 {
    font-size: 20px; font-weight: 700;
    margin: 0 0 2px;
    color: var(--text-dark, #111827);
}
.md-page-header p {
    font-size: 13px; color: var(--text-muted, #6b7280);
    margin: 0;
}

/* Grid */
.md-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
@media (max-width: 768px) {
    .md-grid { grid-template-columns: 1fr; gap: 16px; }
}

/* Card */
.md-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e5e7eb);
    border-radius: 16px;
    overflow: hidden;
}

/* Card Header */
.md-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color, #e5e7eb);
}
.md-card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 15px; font-weight: 600;
    color: var(--text-dark, #111827);
}
.md-card-title .icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #fff;
    flex-shrink: 0;
}
.md-badge-count {
    font-size: 11px; font-weight: 600;
    padding: 2px 8px; border-radius: 999px;
    background: var(--border-color, #e5e7eb);
    color: var(--text-muted, #6b7280);
}

/* Search */
.md-search-wrap {
    position: relative;
}
.md-search-wrap i {
    position: absolute; left: 10px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted, #9ca3af); font-size: 12px;
    pointer-events: none;
}
.md-search-wrap input {
    border: 1.5px solid var(--border-color, #e5e7eb);
    border-radius: 999px;
    padding: 6px 12px 6px 30px;
    font-size: 12px; outline: none;
    width: 150px;
    background: var(--input-bg, #f9fafb);
    color: var(--text-dark, #111827);
    transition: border-color .2s;
}
.md-search-wrap input:focus { border-color: var(--primary, #4f46e5); }

/* Card Body */
.md-card-body { padding: 18px 20px; }

/* Add Form */
.md-add-form {
    display: flex; gap: 8px;
    margin-bottom: 16px;
}
.md-add-form input {
    flex: 1; min-width: 0;
}
.md-add-form .md-btn-add {
    flex-shrink: 0;
    display: flex; align-items: center; gap: 6px;
    padding: 8px 14px;
    border-radius: 10px;
    border: none; cursor: pointer;
    font-size: 13px; font-weight: 600;
    color: #fff;
    transition: opacity .2s, transform .15s;
    white-space: nowrap;
}
.md-add-form .md-btn-add:hover { opacity: .88; transform: translateY(-1px); }
.md-add-form .md-btn-add:active { transform: translateY(0); }
@media (max-width: 480px) {
    .md-add-form { flex-direction: column; }
    .md-add-form .md-btn-add { justify-content: center; }
}

/* List */
.md-list {
    display: flex; flex-direction: column;
    gap: 6px;
    max-height: 340px;
    overflow-y: auto;
    padding-right: 2px;
}
.md-list::-webkit-scrollbar { width: 4px; }
.md-list::-webkit-scrollbar-thumb { background: var(--border-color, #e5e7eb); border-radius: 4px; }

.md-list-item {
    display: flex; align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 10px;
    background: var(--table-hover, #f8fafc);
    border: 1px solid var(--border-color, #f1f5f9);
    transition: background .15s;
}
.md-list-item:hover { background: var(--border-color, #f1f5f9); }

.md-item-left {
    display: flex; align-items: center; gap: 10px;
    min-width: 0;
}
.md-item-num {
    font-size: 11px; font-weight: 700;
    width: 24px; height: 24px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: #fff;
}
.md-item-name {
    font-size: 14px; font-weight: 500;
    color: var(--text-dark, #111827);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.md-item-actions {
    display: flex; gap: 6px; flex-shrink: 0;
}
.md-btn-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: #fff;
    transition: opacity .2s, transform .15s;
}
.md-btn-icon:hover { opacity: .85; transform: scale(1.08); }

.md-empty-state {
    text-align: center; padding: 28px 16px;
    color: var(--text-muted, #9ca3af); font-size: 13px;
}
.md-empty-state i { font-size: 24px; margin-bottom: 8px; display: block; }

/* DataTable pagination override */
.md-dt-wrap .dataTables_paginate { text-align: center; margin-top: 10px; }
.md-dt-wrap .paginate_button { padding: 4px 8px; border-radius: 6px; cursor: pointer; font-size: 12px; }
.md-dt-wrap .paginate_button.current { background: var(--primary, #4f46e5); color: #fff !important; }

/* Modal clean */
.modal-aw .modal-content {
    border-radius: 16px; overflow: hidden;
    border: 1px solid var(--border-color, #e5e7eb);
}
</style>

{{-- Page Header --}}
<div class="md-page-header">
    <div class="md-icon"><i class="fa fa-database"></i></div>
    <div>
        <h1>Master Data Management</h1>
        <p>Manage units, result templates, reference ranges &amp; flag templates</p>
    </div>
</div>

{{-- 2-column responsive grid --}}
<div class="md-grid">

    {{-- ① Laboratory Units --}}
    <div class="md-card">
        <div class="md-card-header">
            <div class="md-card-title">
                <div class="icon" style="background:#4f46e5;"><i class="fa fa-flask"></i></div>
                Laboratory Units
                <span class="md-badge-count">{{ count($units) }}</span>
            </div>
            <div class="md-search-wrap">
                <i class="fa fa-search"></i>
                <input type="text" id="unit-search" placeholder="Search…" autocomplete="off" name="name_1024">
            </div>
        </div>
        <div class="md-card-body">
            <form id="form-add-unit" class="md-add-form">
                @csrf
                <input type="text" name="name" class="form-control-aw" placeholder="New unit (e.g. mg/dl)" required autocomplete="off" id="field_1025">
                <button type="submit" class="md-btn-add" style="background:#4f46e5;">
                    <i class="fa fa-plus"></i> Add
                </button>
            </form>
            <div class="md-list" id="units-list">
                @forelse($units as $i => $unit)
                <div class="md-list-item" data-name="{{ strtolower($unit->name) }}">
                    <div class="md-item-left">
                        <span class="md-item-num" style="background:#4f46e5;">{{ $i + 1 }}</span>
                        <span class="md-item-name">{{ $unit->name }}</span>
                    </div>
                    <div class="md-item-actions">
                        <button class="md-btn-icon btn-edit-unit" style="background:#4f46e5;"
                            data-id="{{ $unit->id }}" data-name="{{ $unit->name }}"
                            data-bs-toggle="modal" data-bs-target="#modal-edit-master" title="Edit">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="md-btn-icon btn-delete-unit" style="background:#ef4444;"
                            data-id="{{ $unit->id }}" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="md-empty-state"><i class="fa fa-box-open"></i>No units added yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ② Result Templates --}}
    <div class="md-card">
        <div class="md-card-header">
            <div class="md-card-title">
                <div class="icon" style="background:#059669;"><i class="fa fa-list-check"></i></div>
                Result Templates
                <span class="md-badge-count">{{ count($templates) }}</span>
            </div>
            <div class="md-search-wrap">
                <i class="fa fa-search"></i>
                <input type="text" id="template-search" placeholder="Search…" autocomplete="off" name="name_1026">
            </div>
        </div>
        <div class="md-card-body">
            <form id="form-add-template" class="md-add-form">
                @csrf
                <input type="text" name="name" class="form-control-aw" placeholder="New result (e.g. Positive)" required autocomplete="off" id="field_1027">
                <button type="submit" class="md-btn-add" style="background:#059669;">
                    <i class="fa fa-plus"></i> Add
                </button>
            </form>
            <div class="md-list" id="templates-list">
                @forelse($templates as $i => $template)
                <div class="md-list-item" data-name="{{ strtolower($template->name) }}">
                    <div class="md-item-left">
                        <span class="md-item-num" style="background:#059669;">{{ $i + 1 }}</span>
                        <span class="md-item-name">{{ $template->name }}</span>
                    </div>
                    <div class="md-item-actions">
                        <button class="md-btn-icon btn-edit-template" style="background:#059669;"
                            data-id="{{ $template->id }}" data-name="{{ $template->name }}"
                            data-bs-toggle="modal" data-bs-target="#modal-edit-master" title="Edit">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="md-btn-icon btn-delete-template" style="background:#ef4444;"
                            data-id="{{ $template->id }}" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="md-empty-state"><i class="fa fa-box-open"></i>No result templates added yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ③ Reference Range Templates --}}
    <div class="md-card">
        <div class="md-card-header">
            <div class="md-card-title">
                <div class="icon" style="background:#2563eb;"><i class="fa fa-file-medical"></i></div>
                Reference Ranges
                <span class="md-badge-count">{{ count($referenceTemplates) }}</span>
            </div>
            <div class="md-search-wrap">
                <i class="fa fa-search"></i>
                <input type="text" id="reference-search" placeholder="Search…" autocomplete="off" name="name_1028">
            </div>
        </div>
        <div class="md-card-body">
            <form id="form-add-reference" class="md-add-form">
                @csrf
                <input type="text" name="name" class="form-control-aw" placeholder="New range (e.g. 70 - 110)" required autocomplete="off" id="field_1029">
                <button type="submit" class="md-btn-add" style="background:#2563eb;">
                    <i class="fa fa-plus"></i> Add
                </button>
            </form>
            <div class="md-list" id="references-list">
                @forelse($referenceTemplates as $i => $ref)
                <div class="md-list-item" data-name="{{ strtolower($ref->name) }}">
                    <div class="md-item-left">
                        <span class="md-item-num" style="background:#2563eb;">{{ $i + 1 }}</span>
                        <span class="md-item-name">{{ $ref->name }}</span>
                    </div>
                    <div class="md-item-actions">
                        <button class="md-btn-icon btn-edit-reference" style="background:#2563eb;"
                            data-id="{{ $ref->id }}" data-name="{{ $ref->name }}"
                            data-bs-toggle="modal" data-bs-target="#modal-edit-master" title="Edit">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="md-btn-icon btn-delete-reference" style="background:#ef4444;"
                            data-id="{{ $ref->id }}" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="md-empty-state"><i class="fa fa-box-open"></i>No reference templates added yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ④ Flag Templates --}}
    <div class="md-card">
        <div class="md-card-header">
            <div class="md-card-title">
                <div class="icon" style="background:#d97706;"><i class="fa fa-flag"></i></div>
                Flag Templates
                <span class="md-badge-count">{{ count($flagTemplates) }}</span>
            </div>
            <div class="md-search-wrap">
                <i class="fa fa-search"></i>
                <input type="text" id="flag-search" placeholder="Search…" autocomplete="off" name="name_1030">
            </div>
        </div>
        <div class="md-card-body">
            <form id="form-add-flag" class="md-add-form">
                @csrf
                <input type="text" name="name" class="form-control-aw" placeholder="New flag (e.g. H, L, C)" required autocomplete="off" id="field_1031">
                <button type="submit" class="md-btn-add" style="background:#d97706;">
                    <i class="fa fa-plus"></i> Add
                </button>
            </form>
            <div class="md-list" id="flags-list">
                @forelse($flagTemplates as $i => $flg)
                <div class="md-list-item" data-name="{{ strtolower($flg->name) }}">
                    <div class="md-item-left">
                        <span class="md-item-num" style="background:#d97706;">{{ $i + 1 }}</span>
                        <span class="md-item-name">{{ $flg->name }}</span>
                    </div>
                    <div class="md-item-actions">
                        <button class="md-btn-icon btn-edit-flag" style="background:#d97706;"
                            data-id="{{ $flg->id }}" data-name="{{ $flg->name }}"
                            data-bs-toggle="modal" data-bs-target="#modal-edit-master" title="Edit">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="md-btn-icon btn-delete-flag" style="background:#ef4444;"
                            data-id="{{ $flg->id }}" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="md-empty-state"><i class="fa fa-box-open"></i>No flag templates added yet.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>{{-- /.md-grid --}}

{{-- Universal Edit Modal --}}
<div class="modal fade modal-aw" id="modal-edit-master" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-edit-master">
                    <input type="hidden" id="edit-id" name="name_1032">
                    <input type="hidden" id="edit-type" name="name_1033">
                    <div class="mb-2">
                        <label for="edit-name" class="form-label-aw">Name / Value</label>
                        <input type="text" id="edit-name" name="name" class="form-control-aw" required autocomplete="off">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-update-master">
                    <i class="fa fa-check me-1"></i> Update
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {

    /* ── Live search helpers ──────────────────────────────── */
    function liveSearch(inputId, listId) {
        $('#' + inputId).on('input', function () {
            var q = $(this).val().toLowerCase().trim();
            $('#' + listId + ' .md-list-item').each(function () {
                var match = $(this).data('name').indexOf(q) !== -1;
                $(this).toggle(match || q === '');
            });
        });
    }
    liveSearch('unit-search',      'units-list');
    liveSearch('template-search',  'templates-list');
    liveSearch('reference-search', 'references-list');
    liveSearch('flag-search',      'flags-list');

    /* ── ADD handlers ─────────────────────────────────────── */
    $('#form-add-unit').submit(function (e) {
        e.preventDefault();
        $.post("{{ route('units.store') }}", $(this).serialize(), function () { location.reload(); });
    });
    $('#form-add-template').submit(function (e) {
        e.preventDefault();
        $.post("{{ route('result-templates.store') }}", $(this).serialize(), function () { location.reload(); });
    });
    $('#form-add-reference').submit(function (e) {
        e.preventDefault();
        $.post("{{ route('reference-templates.store') }}", $(this).serialize(), function () { location.reload(); });
    });
    $('#form-add-flag').submit(function (e) {
        e.preventDefault();
        $.post("{{ route('flag-templates.store') }}", $(this).serialize(), function () { location.reload(); });
    });

    /* ── DELETE handlers ──────────────────────────────────── */
    $(document).on('click', '.btn-delete-unit', function () {
        if (confirm('Remove this unit?'))
            $.ajax({ url: '/units/' + $(this).data('id'), type: 'DELETE', success: function () { location.reload(); } });
    });
    $(document).on('click', '.btn-delete-template', function () {
        if (confirm('Remove this template?'))
            $.ajax({ url: '/result-templates/' + $(this).data('id'), type: 'DELETE', success: function () { location.reload(); } });
    });
    $(document).on('click', '.btn-delete-reference', function () {
        if (confirm('Remove this reference template?'))
            $.ajax({ url: '/reference-templates/' + $(this).data('id'), type: 'DELETE', success: function () { location.reload(); } });
    });
    $(document).on('click', '.btn-delete-flag', function () {
        if (confirm('Remove this flag template?'))
            $.ajax({ url: '/flag-templates/' + $(this).data('id'), type: 'DELETE', success: function () { location.reload(); } });
    });

    /* ── POPULATE edit modal ──────────────────────────────── */
    $(document).on('click', '.btn-edit-unit', function () {
        $('#edit-id').val($(this).data('id'));
        $('#edit-name').val($(this).data('name'));
        $('#edit-type').val('unit');
        $('.modal-title').html('<i class="fa fa-pen me-2"></i>Edit Laboratory Unit');
    });
    $(document).on('click', '.btn-edit-template', function () {
        $('#edit-id').val($(this).data('id'));
        $('#edit-name').val($(this).data('name'));
        $('#edit-type').val('template');
        $('.modal-title').html('<i class="fa fa-pen me-2"></i>Edit Result Template');
    });
    $(document).on('click', '.btn-edit-reference', function () {
        $('#edit-id').val($(this).data('id'));
        $('#edit-name').val($(this).data('name'));
        $('#edit-type').val('reference-template');
        $('.modal-title').html('<i class="fa fa-pen me-2"></i>Edit Reference Template');
    });
    $(document).on('click', '.btn-edit-flag', function () {
        $('#edit-id').val($(this).data('id'));
        $('#edit-name').val($(this).data('name'));
        $('#edit-type').val('flag-template');
        $('.modal-title').html('<i class="fa fa-pen me-2"></i>Edit Flag Template');
    });

    /* ── UPDATE ───────────────────────────────────────────── */
    $('#btn-update-master').click(function () {
        var id   = $('#edit-id').val();
        var type = $('#edit-type').val();
        var urls = {
            'unit':               '/units/'              + id,
            'template':           '/result-templates/'   + id,
            'reference-template': '/reference-templates/'+ id,
            'flag-template':      '/flag-templates/'     + id,
        };
        $.ajax({
            url:     urls[type],
            type:    'PUT',
            data:    $('#form-edit-master').serialize(),
            success: function ()  { location.reload(); },
            error:   function ()  { alert('Error updating entry. Possibly a duplicate name.'); }
        });
    });

});
</script>
@endpush
@endsection
