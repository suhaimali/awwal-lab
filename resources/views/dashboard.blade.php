@extends('layouts.app')
@section('title', ' | Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-th-large"></i></div>
        <div>
            <div>Overview</div>
            <div style="font-size:13px; font-weight:400; color:var(--text-muted); margin-top:2px;">Welcome back — here's what's happening today.</div>
        </div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">

        <a href="{{ route('patients') }}" class="btn-aw-primary">
            <i class="fa fa-user-plus"></i> Add Patient
        </a>
        <a href="{{ route('reports') }}" class="btn-aw-outline">
            <i class="fa fa-file-medical"></i> View Reports
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="stat-card-new">
            <div class="stat-icon-circle stat-icon-blue">
                <i class="fa fa-users"></i>
            </div>
            <div class="stat-text">
                <div class="stat-num" id="stat-patients">{{ $totalPatients }}</div>
                <div class="stat-lbl">Total Patients</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="stat-card-new">
            <div class="stat-icon-circle stat-icon-green">
                <i class="fa fa-circle-check"></i>
            </div>
            <div class="stat-text">
                <div class="stat-num" id="stat-completed">{{ $totalCompleted }}</div>
                <div class="stat-lbl">Completed Reports</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="stat-card-new">
            <div class="stat-icon-circle stat-icon-orange">
                <i class="fa fa-calendar-check"></i>
            </div>
            <div class="stat-text">
                <div class="stat-num" id="stat-appointments">{{ $totalAppointments }}</div>
                <div class="stat-lbl">Total Bookings</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="stat-card-new">
            <div class="stat-icon-circle stat-icon-purple">
                <i class="fa fa-clock"></i>
            </div>
            <div class="stat-text">
                <div class="stat-num" id="stat-pending">{{ $pendingReports }}</div>
                <div class="stat-lbl">Pending Reports</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Welcome Banner -->
<div class="row g-3 mb-4">
    <!-- Welcome banner -->
    <div class="col-lg-8">
        <div class="aw-card" style="background: linear-gradient(135deg, #1a56db 0%, #3b82f6 60%, #60a5fa 100%); border:none; color:#fff; min-height:180px; position:relative; overflow:hidden;">
            <div style="position:absolute; right:-20px; top:-20px; width:180px; height:180px; background:rgba(255,255,255,0.07); border-radius:50%;"></div>
            <div style="position:absolute; right:60px; bottom:-40px; width:140px; height:140px; background:rgba(255,255,255,0.06); border-radius:50%;"></div>
            <div class="aw-card-body" style="position:relative; z-index:1; padding:32px;">
                @php
                    $hour = date('H');
                    if ($hour < 12) {
                        $greeting = 'Good Morning';
                    } elseif ($hour < 17) {
                        $greeting = 'Good Afternoon';
                    } else {
                        $greeting = 'Good Evening';
                    }
                @endphp
                <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:2px; opacity:0.75; margin-bottom:8px;">Command Center</div>
                <h2 style="font-size:24px; font-weight:800; margin-bottom:10px; line-height:1.2;">{{ $greeting }}, {{ auth()->user()->name ?? 'Administrator' }}</h2>
                <p style="font-size:13.5px; opacity:0.85; max-width:550px; line-height:1.6;">Your diagnostic center dashboard is active. Today, you have registered <strong>{{ $totalPatients }} patients</strong> and finalized <strong>{{ $totalCompleted }} test reports</strong>. There are currently <strong>{{ $pendingReports }} pending reports</strong> awaiting action.</p>
                <div style="display:flex; gap:12px; margin-top:20px; flex-wrap:wrap;">
                    <a href="{{ route('patients') }}" style="background:rgba(255,255,255,0.2); color:#fff; padding:9px 20px; border-radius:9px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:7px; backdrop-filter:blur(4px); transition:all 0.2s; border:1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        <i class="fa fa-users"></i> Manage Patients
                    </a>
                    <a href="{{ route('reports') }}" style="background:rgba(255,255,255,0.1); color:#fff; padding:9px 20px; border-radius:9px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:7px; border:1px solid rgba(255,255,255,0.25); transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.18)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fa fa-file-medical"></i> View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick links -->
    <div class="col-lg-4">
        <div class="aw-card h-100">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-bolt" style="color:#f59e0b;"></i> Quick Actions</div>
            </div>
            <div class="aw-card-body" style="padding:12px 16px;">
                <a href="{{ route('appointments') }}" class="d-flex align-items-center gap-12 p-10 rounded-3 mb-2 text-decoration-none" style="gap:12px; padding:10px 12px; border-radius:10px; transition:all 0.2s; color:var(--text-dark);" onmouseover="this.style.background='var(--primary-light)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;background:#e0e7ff;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa fa-calendar-plus" style="color:#6366f1;"></i></div>
                    <div>
                        <div style="font-size:13px;font-weight:600;">Book Appointment</div>
                        <div style="font-size:11px;color:var(--text-muted);">Schedule a new visit</div>
                    </div>
                    <i class="fa fa-chevron-right ms-auto" style="font-size:11px;color:var(--text-muted);"></i>
                </a>
                <a href="{{ route('lab-tests.index') }}" class="d-flex align-items-center gap-12 p-10 rounded-3 mb-2 text-decoration-none" style="gap:12px; padding:10px 12px; border-radius:10px; transition:all 0.2s; color:var(--text-dark);" onmouseover="this.style.background='var(--primary-light)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;background:#d1fae5;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa fa-flask" style="color:#059669;"></i></div>
                    <div>
                        <div style="font-size:13px;font-weight:600;">Lab Tests</div>
                        <div style="font-size:11px;color:var(--text-muted);">Billing & test management</div>
                    </div>
                    <i class="fa fa-chevron-right ms-auto" style="font-size:11px;color:var(--text-muted);"></i>
                </a>
                <a href="{{ route('payments') }}" class="d-flex align-items-center gap-12 p-10 rounded-3 mb-2 text-decoration-none" style="gap:12px; padding:10px 12px; border-radius:10px; transition:all 0.2s; color:var(--text-dark);" onmouseover="this.style.background='var(--primary-light)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;background:#fff7ed;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa fa-credit-card" style="color:#ea580c;"></i></div>
                    <div>
                        <div style="font-size:13px;font-weight:600;">Payments</div>
                        <div style="font-size:11px;color:var(--text-muted);">Billing & receipts</div>
                    </div>
                    <i class="fa fa-chevron-right ms-auto" style="font-size:11px;color:var(--text-muted);"></i>
                </a>
                <a href="#" onclick="openIncomeReport(event)" class="d-flex align-items-center gap-12 p-10 rounded-3 text-decoration-none" style="gap:12px; padding:10px 12px; border-radius:10px; transition:all 0.2s; color:var(--text-dark);" onmouseover="this.style.background='var(--primary-light)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;background:#f3e8ff;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa fa-chart-line" style="color:#9333ea;"></i></div>
                    <div>
                        <div style="font-size:13px;font-weight:600;">Income Report</div>
                        <div style="font-size:11px;color:var(--text-muted);">Financial overview</div>
                    </div>
                    <i class="fa fa-chevron-right ms-auto" style="font-size:11px;color:var(--text-muted);"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Tiles (All Modules A-Z) -->
<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-grid-2" style="color:var(--primary);"></i> All Modules</div>
    </div>
    <div class="aw-card-body">
        <div class="row g-3">
            @php
            $modules = [
                ['icon'=>'fa-th-large',       'color'=>'#1a56db', 'bg'=>'#e8f0fe', 'label'=>'Dashboard',         'sub'=>'Overview & stats',       'route'=>route('dashboard')],
                ['icon'=>'fa-users',           'color'=>'#059669', 'bg'=>'#d1fae5', 'label'=>'Patients',           'sub'=>'Patient records',         'route'=>route('patients')],
                ['icon'=>'fa-calendar-plus',   'color'=>'#6366f1', 'bg'=>'#e0e7ff', 'label'=>'Book Appointment',  'sub'=>'Schedule visits',         'route'=>route('appointments')],
                ['icon'=>'fa-flask',           'color'=>'#0891b2', 'bg'=>'#cffafe', 'label'=>'Lab Tests',          'sub'=>'Tests & billing',         'route'=>route('lab-tests.index')],
                ['icon'=>'fa-sliders',         'color'=>'#7c3aed', 'bg'=>'#ede9fe', 'label'=>'Test Parameters',   'sub'=>'Clinical ranges',         'route'=>route('test-parameters.index')],
                ['icon'=>'fa-tags',            'color'=>'#b45309', 'bg'=>'#fef3c7', 'label'=>'Master Categories', 'sub'=>'Top-level categories',    'route'=>route('categories.index')],
                ['icon'=>'fa-list-ul',         'color'=>'#b45309', 'bg'=>'#fff7ed', 'label'=>'Sub-Categories',    'sub'=>'Category groupings',      'route'=>route('sub-categories.index')],
                ['icon'=>'fa-database',        'color'=>'#0d9488', 'bg'=>'#ccfbf1', 'label'=>'Master Data',        'sub'=>'Manage units & templates','route'=>route('master-data.index')],
                ['icon'=>'fa-file-medical',    'color'=>'#0369a1', 'bg'=>'#e0f2fe', 'label'=>'Test Reports',      'sub'=>'Generate & print',        'route'=>route('reports')],
                ['icon'=>'fa-signature',       'color'=>'#475569', 'bg'=>'#f1f5f9', 'label'=>'Report Signatures', 'sub'=>'Doctor signatures',       'route'=>route('report-signatures.index')],
                ['icon'=>'fa-credit-card',     'color'=>'#ea580c', 'bg'=>'#fff7ed', 'label'=>'Payments',          'sub'=>'Billing & receipts',      'route'=>route('payments')],
                ['icon'=>'fa-chart-line',      'color'=>'#9333ea', 'bg'=>'#f3e8ff', 'label'=>'Income Report',     'sub'=>'Financial analytics',     'route'=>'#', 'onclick'=>'openIncomeReport(event)'],
            ];
            @endphp
            @foreach($modules as $mod)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <a href="{{ $mod['route'] }}" @if(isset($mod['onclick'])) onclick="{{ $mod['onclick'] }}" @endif
                   class="text-decoration-none d-block text-center p-3 rounded-3"
                   style="border:1.5px solid var(--border-color); transition:all 0.2s; background:var(--white);"
                   onmouseover="this.style.borderColor='{{ $mod['color'] }}';this.style.background='{{ $mod['bg'] }}';this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,0.08)';"
                   onmouseout="this.style.borderColor='var(--border-color)';this.style.background='var(--white)';this.style.transform='none';this.style.boxShadow='none';">
                    <div style="width:48px;height:48px;background:{{ $mod['bg'] }};border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="fa {{ $mod['icon'] }}" style="color:{{ $mod['color'] }};font-size:20px;"></i>
                    </div>
                    <div style="font-size:12.5px;font-weight:700;color:var(--text-dark);">{{ $mod['label'] }}</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">{{ $mod['sub'] }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div style="height:20px;"></div>
@endsection
