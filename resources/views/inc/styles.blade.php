    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1345b7;
            --primary-light: #e8f0fe;
            --primary-hover: #1e64f0;
            --sidebar-w: 260px;
            --sidebar-collapsed: 70px;
            --header-h: 64px;
            --bg-main: #f0f4ff;
            --white: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 1px 8px rgba(26,86,219,0.07);
            --transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-main);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ── SIDEBAR ── */
        #awlab-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--white);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: var(--transition);
            overflow: hidden;
        }
        body.sidebar-collapsed #awlab-sidebar { width: var(--sidebar-collapsed); }

        .sidebar-brand {
            height: var(--header-h);
            display: flex;
            align-items: center;
            padding: 0 18px;
            border-bottom: 1px solid var(--border-color);
            gap: 12px;
            flex-shrink: 0;
            background: var(--white);
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-icon i { color: #fff; font-size: 16px; }
        .sidebar-brand .brand-name {
            font-size: 16px; font-weight: 700;
            color: var(--primary); white-space: nowrap;
            transition: var(--transition);
        }
        body.sidebar-collapsed .sidebar-brand .brand-name { opacity: 0; width: 0; }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 10px;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        .sidebar-section-label {
            font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
            color: var(--text-muted);
            padding: 8px 8px 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: var(--transition);
        }
        body.sidebar-collapsed .sidebar-section-label { opacity: 0; }

        .nav-item-link {
            display: flex; align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: var(--transition);
            margin-bottom: 2px;
            white-space: nowrap;
            position: relative;
        }
        .nav-item-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }
        .nav-item-link.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(26,86,219,0.3);
        }
        .nav-item-link .nav-icon {
            width: 20px; height: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; flex-shrink: 0;
        }
        .nav-item-link .nav-label { flex: 1; transition: var(--transition); }
        body.sidebar-collapsed .nav-item-link .nav-label { opacity: 0; width: 0; overflow: hidden; }
        body.sidebar-collapsed .nav-item-link { justify-content: center; padding: 12px; }
        body.sidebar-collapsed .nav-item-link .nav-icon { margin: 0; }

        /* Tooltip for collapsed state */
        body.sidebar-collapsed .nav-item-link::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sidebar-collapsed) + 8px);
            top: 50%; transform: translateY(-50%);
            background: var(--text-dark);
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
            z-index: 2000;
        }
        body.sidebar-collapsed .nav-item-link:hover::after { opacity: 1; }

        .sidebar-footer {
            padding: 12px 14px;
            border-top: 1px solid var(--border-color);
            font-size: 11px;
            color: var(--text-muted);
            text-align: center;
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }
        body.sidebar-collapsed .sidebar-footer { opacity: 0; }

        /* ── HEADER ── */
        #awlab-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--header-h);
            background: var(--white);
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center;
            padding: 0 24px;
            gap: 16px;
            z-index: 1030;
            transition: left var(--transition);
        }
        body.sidebar-collapsed #awlab-header { left: var(--sidebar-collapsed); }

        .header-toggle-btn {
            width: 36px; height: 36px;
            border: 1px solid var(--border-color);
            background: var(--white);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-muted);
            transition: var(--transition); flex-shrink: 0;
        }
        .header-toggle-btn:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }

        .header-page-title {
            font-size: 16px; font-weight: 600;
            color: var(--text-dark);
            flex: 1;
        }

        .header-clock {
            background: var(--primary-light);
            border-radius: 10px;
            padding: 6px 14px;
            text-align: right;
            display: flex; flex-direction: column;
        }
        .header-clock .time-val { font-size: 13px; font-weight: 700; color: var(--primary); }
        .header-clock .date-val { font-size: 10px; color: var(--text-muted); text-transform: uppercase; }

        .header-avatar-btn {
            display: flex; align-items: center; gap: 10px;
            background: none; border: none; cursor: pointer;
            padding: 4px;
            border-radius: 10px;
            transition: var(--transition);
        }
        .header-avatar-btn:hover { background: var(--primary-light); }
        .header-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 14px;
        }
        .header-user-info { text-align: left; display: none; }
        @media (min-width: 768px) { .header-user-info { display: block; } }
        .header-user-info .u-name { font-size: 13px; font-weight: 600; color: var(--text-dark); }
        .header-user-info .u-role { font-size: 10px; color: var(--text-muted); text-transform: uppercase; }

        /* ── MAIN CONTENT ── */
        #awlab-main {
            margin-left: var(--sidebar-w);
            margin-top: var(--header-h);
            min-height: calc(100vh - var(--header-h));
            padding: 28px 28px 0;
            transition: margin-left var(--transition);
        }
        body.sidebar-collapsed #awlab-main { margin-left: var(--sidebar-collapsed); }

        /* ── FOOTER ── */
        #awlab-footer {
            margin-left: var(--sidebar-w);
            padding: 16px 28px;
            border-top: 1px solid var(--border-color);
            background: var(--white);
            font-size: 12px;
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: space-between;
            transition: margin-left var(--transition);
        }
        body.sidebar-collapsed #awlab-footer { margin-left: var(--sidebar-collapsed); }
        #awlab-footer a { color: var(--primary); text-decoration: none; }
        #awlab-footer a:hover { text-decoration: underline; }

        /* ── PAGE CARDS ── */
        .aw-card {
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        .aw-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; flex-wrap: wrap;
        }
        .aw-card-title {
            font-size: 15px; font-weight: 700;
            color: var(--text-dark);
            display: flex; align-items: center; gap: 8px;
        }
        .aw-card-body { padding: 20px 24px; }

        /* ── STAT CARDS ── */
        .stat-card-new {
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            padding: 20px 22px;
            display: flex; align-items: center; gap: 16px;
            transition: var(--transition);
        }
        .stat-card-new:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(26,86,219,0.13);
        }
        .stat-icon-circle {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }
        .stat-icon-blue { background: var(--primary-light); color: var(--primary); }
        .stat-icon-green { background: #d1fae5; color: #059669; }
        .stat-icon-orange { background: #fff7ed; color: #ea580c; }
        .stat-icon-purple { background: #f3e8ff; color: #9333ea; }
        .stat-text .stat-num {
            font-size: 26px; font-weight: 800;
            color: var(--text-dark); line-height: 1;
        }
        .stat-text .stat-lbl { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-top: 4px; }

        /* ── TABLES (MODERN REDESIGN) ── */
        .table-responsive-modern {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-color);
            background: var(--white);
            margin-bottom: 15px;
        }
        .table-modern { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0; 
            margin: 0 !important;
        }
        .table-modern thead th {
            background: #f8fafc;
            color: var(--text-muted);
            font-size: 11.5px; 
            font-weight: 700;
            text-transform: uppercase; 
            letter-spacing: 0.8px;
            padding: 10px 14px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }
        .table-modern tbody tr { 
            transition: all 0.2s ease;
            background: var(--white);
        }
        .table-modern tbody tr:hover { 
            background: #eef2ff;
        }
        .table-modern tbody tr:hover:not(:has(td[colspan])):not(.no-results-row) { 
            background: #eef2ff;
            box-shadow: inset 0 0 0 9999px rgba(26,86,219,0.06), inset 3px 0 0 var(--primary);
        }
        .table-modern tbody td {
            padding: 10px 14px;
            font-size: 13.5px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }
        /* DataTables Overrides for Modern Look */
        div.dataTables_wrapper div.dataTables_filter input {
            border-radius: 20px;
            padding: 6px 16px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
        }
        div.dataTables_wrapper div.dataTables_filter input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,86,219,0.12);
            outline: none;
        }
        div.dataTables_wrapper div.dataTables_length select {
            border-radius: 8px;
            padding: 4px 30px 4px 10px;
            border: 1px solid #cbd5e1;
        }
        .pagination .page-item .page-link {
            border: none;
            color: var(--text-muted);
            border-radius: 8px;
            margin: 0 2px;
            font-size: 13px;
            font-weight: 600;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            color: #fff;
            box-shadow: 0 4px 10px rgba(26,86,219,0.2);
        }

        /* ── BUTTONS ── */
        .btn-aw-primary {
            background: var(--primary);
            color: #fff; border: none;
            border-radius: 9px;
            padding: 9px 20px;
            font-size: 13px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 7px;
            cursor: pointer; transition: var(--transition);
            text-decoration: none;
        }
        .btn-aw-primary:hover { background: var(--primary-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(26,86,219,0.3); text-decoration: none; }
        .btn-aw-outline {
            background: transparent;
            color: var(--primary); border: 1.5px solid var(--primary);
            border-radius: 9px; padding: 8px 18px;
            font-size: 13px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 7px;
            cursor: pointer; transition: var(--transition);
            text-decoration: none;
        }
        .btn-aw-outline:hover { background: var(--primary-light); color: var(--primary); text-decoration: none; }
        .btn-aw-danger {
            background: #fee2e2; color: #dc2626; border: none;
            border-radius: 8px; padding: 7px 14px;
            font-size: 12px; font-weight: 600; cursor: pointer; transition: var(--transition);
            text-decoration: none;
        }
        .btn-aw-danger:hover { background: #fca5a5; color: #dc2626; text-decoration: none; }
        .btn-aw-success {
            background: #d1fae5; color: #059669; border: none;
            border-radius: 8px; padding: 7px 14px;
            font-size: 12px; font-weight: 600; cursor: pointer; transition: var(--transition);
            text-decoration: none;
        }
        .btn-aw-success:hover { background: #6ee7b7; color: #059669; text-decoration: none; }
        .btn-aw-sm { padding: 5px 12px; font-size: 12px; }

        /* ── BADGES ── */
        .badge-aw {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .badge-blue { background: var(--primary-light); color: var(--primary); }
        .badge-green { background: #d1fae5; color: #059669; }
        .badge-orange { background: #fff7ed; color: #ea580c; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-purple { background: #f3e8ff; color: #9333ea; }

        /* ── FORMS ── */
        .form-label-aw { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px; }
        .form-control-aw {
            width: 100%; border: 1.5px solid var(--border-color);
            border-radius: 9px; padding: 10px 14px;
            font-size: 13.5px; color: var(--text-dark);
            background: var(--white); transition: var(--transition);
            outline: none; font-family: 'Inter', sans-serif;
        }
        .form-control-aw:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,86,219,0.12); }
        .form-control-aw::placeholder { color: #94a3b8; }

        /* ── MODALS ── */
        .modal-aw .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-aw .modal-header {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            border-radius: 16px 16px 0 0;
            padding: 18px 24px;
            border: none;
        }
        .modal-aw .modal-title { color: #fff; font-size: 16px; font-weight: 700; }
        .modal-aw .btn-close { filter: invert(1); }
        .modal-aw .modal-body { padding: 24px; }
        .modal-aw .modal-footer { padding: 16px 24px; border-color: var(--border-color); }

        /* ── PAGE HEADER ── */
        .page-header-aw {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
            margin-bottom: 24px;
        }
        .page-header-aw .page-title-aw {
            font-size: 22px; font-weight: 800; color: var(--text-dark);
            display: flex; align-items: center; gap: 10px;
        }
        .page-header-aw .page-title-aw .title-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 18px;
        }

        /* ── LOADER ── */
        #aw-loader {
            position: fixed; inset: 0;
            background: rgba(240,244,255,0.8);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(4px);
            transition: opacity 0.3s;
        }
        #aw-loader.hidden { opacity: 0; pointer-events: none; }
        .aw-spinner {
            width: 44px; height: 44px;
            border: 4px solid var(--primary-light);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── MOBILE OVERLAY ── */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 991px) {
            #awlab-sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-w) !important;
            }
            body.sidebar-open #awlab-sidebar { transform: translateX(0); }
            body.sidebar-open #sidebar-overlay { display: block; }
            #awlab-header { left: 0 !important; }
            #awlab-main { margin-left: 0 !important; padding: 20px 16px 0; }
            #awlab-footer { margin-left: 0 !important; }
        }
        @media (max-width: 575px) {
            .header-clock { display: none; }
            #awlab-main { padding: 14px 12px 0; }
            
            /* Mobile Modal Responsiveness */
            .modal-dialog {
                margin: 10px;
                max-width: calc(100% - 20px) !important;
            }
            .modal-aw .modal-body {
                padding: 16px;
            }

            /* Fix input groups with many buttons wrapping nicely */
            .modal-aw .input-group.flex-nowrap {
                flex-wrap: wrap !important;
            }
            .modal-aw .input-group.flex-nowrap > .form-select,
            .modal-aw .input-group.flex-nowrap > .form-control {
                flex: 1 1 100% !important;
                width: 100% !important;
                border-radius: 9px !important;
                margin-bottom: 8px;
            }
            .modal-aw .input-group.flex-nowrap > .btn {
                flex: 1 1 auto;
                border-radius: 6px !important;
                margin-right: 4px;
                margin-bottom: 4px;
            }

            /* Stack two-column detail tables (th/td pairs) */
            .modal-aw .table-responsive-modern table tr {
                display: flex;
                flex-direction: column;
                border-bottom: 1px solid #eee;
            }
            .modal-aw .table-responsive-modern table th,
            .modal-aw .table-responsive-modern table td {
                width: 100% !important;
                display: block;
                border: none !important;
            }
            .modal-aw .table-responsive-modern table th {
                padding-bottom: 2px !important;
                background: transparent !important;
                color: #64748b;
                font-size: 11px;
            }
            .modal-aw .table-responsive-modern table td {
                padding-top: 0 !important;
                padding-bottom: 12px !important;
                font-size: 14px;
                font-weight: 500;
            }
        }

        /* DataTables override */
        .dataTables_wrapper .dataTables_filter input {
            border: 1.5px solid var(--border-color);
            border-radius: 8px; padding: 6px 12px;
            font-family: 'Inter', sans-serif; font-size: 13px;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1.5px solid var(--border-color);
            border-radius: 8px; padding: 4px 8px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #fff !important;
            border-radius: 6px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--primary-light) !important;
            border-color: var(--primary-light) !important;
            color: var(--primary) !important;
            border-radius: 6px;
        }

        /* Select2 override */
        .select2-container--bootstrap-5 .select2-selection {
            border: 1.5px solid var(--border-color) !important;
            border-radius: 9px !important;
            min-height: 42px !important;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(26,86,219,0.12) !important;
        }
        /* Fix Select2 inside Bootstrap input-groups */
        .input-group > .select2-container--bootstrap-5 {
            flex: 1 1 auto;
            width: 1% !important;
        }
        .input-group > .select2-container--bootstrap-5 .select2-selection {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        /* ── MOBILE TABLE RESPONSIVENESS (STACKING CARD VIEW) ── */
        @media (max-width: 767px) {
            .table-responsive-modern {
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                overflow-x: visible !important;
            }
            .table-modern, 
            .table-modern tbody, 
            .table-modern tr, 
            .table-modern td {
                display: block !important;
                width: 100% !important;
            }
            .table-modern thead {
                display: none !important;
            }
            .table-modern tbody tr {
                background: var(--white) !important;
                border-radius: 12px !important;
                margin-bottom: 16px !important;
                padding: 12px 16px !important;
                box-shadow: 0 2px 10px rgba(0,0,0,0.04) !important;
                border: 1px solid var(--border-color) !important;
                position: relative;
            }
            .table-modern tbody tr:hover,
            .table-modern tbody tr:hover:not(:has(td[colspan])):not(.no-results-row) {
                background: var(--white) !important;
                box-shadow: 0 4px 15px rgba(26,86,219,0.08) !important;
            }
            .table-modern tbody td {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                text-align: right !important;
                padding: 8px 0 !important;
                border-bottom: 1px dashed #f1f5f9 !important;
                font-size: 13px !important;
            }
            .table-modern tbody td:last-child {
                border-bottom: none !important;
            }
            .table-modern tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--text-muted);
                text-transform: uppercase;
                font-size: 10px;
                letter-spacing: 0.5px;
                text-align: left;
                padding-right: 12px;
                flex-shrink: 0;
            }
            /* If cell has no data-label, display normally */
            .table-modern tbody td:not([data-label]) {
                justify-content: flex-end !important;
            }
            .table-modern tbody td:not([data-label])::before {
                display: none !important;
            }
            /* Exclude empty/no results state rows from stacking layout */
            .table-modern tbody tr:has(td[colspan]) {
                display: block !important;
                text-align: center !important;
                padding: 24px 16px !important;
            }
            .table-modern tbody tr:has(td[colspan]) td {
                display: block !important;
                text-align: center !important;
                border: none !important;
                padding: 0 !important;
            }
            .table-modern tbody tr:has(td[colspan]) td::before {
                display: none !important;
            }
            /* Action alignment inside card */
            .action-btn-group, .action-buttons {
                width: 100% !important;
                justify-content: flex-end !important;
            }
        }
    </style>
