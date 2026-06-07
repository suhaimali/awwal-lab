@extends('layouts.app')
@section('title', ' | Income Report')
@section('page-title', 'Income Report')

@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-chart-line"></i></div>
        <div>
            <div>Financial Insights</div>
            <div style="font-size:13px; font-weight:400; color:var(--text-muted); margin-top:2px;">Track revenue trends, payment methods, and transactions</div>
        </div>
    </div>
</div>

<!-- Filter Box -->
<div class="aw-card mb-4">
    <div class="aw-card-body">
        <form action="{{ route('income-report') }}" method="GET" class="row align-items-end g-3">
            <div class="col-12 col-md-4">
                <label for="field_1022" class="form-label-aw">Start Date</label>
                <input type="date" class="form-control-aw" name="start_date" value="{{ request('start_date', date('Y-m-d')) }}" autocomplete="off" id="field_1022">
            </div>
            <div class="col-12 col-md-4">
                <label for="field_1023" class="form-label-aw">End Date</label>
                <input type="date" class="form-control-aw" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}" autocomplete="off" id="field_1023">
            </div>
            <div class="col-12 col-md-4">
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-aw-primary" style="flex:1; justify-content:center;"><i class="fa fa-filter"></i> Apply Filter</button>
                    <a href="{{ route('income-report') }}" class="btn-aw-outline" style="flex:1; justify-content:center;"><i class="fa fa-undo"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="stat-card-new">
            <div class="stat-icon-circle stat-icon-green">
                <i class="fa fa-money-bill-wave"></i>
            </div>
            <div class="stat-text">
                <div class="stat-num">₹{{ number_format($totalIncome, 2) }}</div>
                <div class="stat-lbl">Total Income</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="stat-card-new">
            <div class="stat-icon-circle stat-icon-blue">
                <i class="fa fa-receipt"></i>
            </div>
            <div class="stat-text">
                <div class="stat-num">{{ $totalTransactions }}</div>
                <div class="stat-lbl">Transactions</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="stat-card-new">
            <div class="stat-icon-circle stat-icon-orange">
                <i class="fa fa-chart-line"></i>
            </div>
            <div class="stat-text">
                <div class="stat-num">₹{{ number_format($averageTransaction, 2) }}</div>
                <div class="stat-lbl">Avg. Ticket Size</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="stat-card-new">
            <div class="stat-icon-circle stat-icon-purple">
                <i class="fa fa-calendar-alt"></i>
            </div>
            <div class="stat-text">
                <div class="stat-num">{{ count($dailyTrend) }} Days</div>
                <div class="stat-lbl">Active Days</div>
            </div>
        </div>
    </div>
</div>

<!-- Breakdown & Recent Transactions -->
<div class="row g-4 mb-4">
    <!-- Payment Methods -->
    <div class="col-xl-5 col-lg-6 col-12">
        <div class="aw-card h-100">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-credit-card" style="color:var(--primary);"></i> Payment Methods</div>
            </div>
            <div class="aw-card-body">
                @if(count($methods) > 0)
                    @php
                        $colors = ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'];
                        $colorIndex = 0;
                    @endphp
                    @foreach($methods as $method)
                        @php
                            $percentage = $totalIncome > 0 ? ($method['amount'] / $totalIncome) * 100 : 0;
                            $color = $colors[$colorIndex % count($colors)];
                            $colorIndex++;
                            
                            $icon = 'fa-credit-card';
                            $methodName = strtolower($method['method']);
                            if(str_contains($methodName, 'cash')) $icon = 'fa-money-bill';
                            elseif(str_contains($methodName, 'upi') || str_contains($methodName, 'online')) $icon = 'fa-mobile-alt';
                            elseif(str_contains($methodName, 'card')) $icon = 'fa-credit-card';
                        @endphp
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:32px; height:32px; background:var(--primary-light); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                        <i class="fa {{ $icon }}" style="color:var(--primary); font-size:14px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13.5px; font-weight:600;">{{ $method['method'] }}</div>
                                        <div style="font-size:11px; color:var(--text-muted);">{{ $method['count'] }} transactions</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div style="font-size:13.5px; font-weight:700;">₹{{ number_format($method['amount'], 2) }}</div>
                                    <span class="badge-aw badge-blue" style="font-size:10px; padding:2px 6px;">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </div>
                            <div class="progress" style="height: 6px; background:#f1f5f9; border-radius:10px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $color }}; border-radius:10px;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-credit-card" style="font-size:40px; color:var(--text-muted); opacity:0.3; display:block; margin-bottom:10px;"></i>
                        <div style="font-size:14px; color:var(--text-muted);">No payment data available.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-xl-7 col-lg-6 col-12">
        <div class="aw-card h-100">
            <div class="aw-card-header">
                <div class="aw-card-title"><i class="fa fa-history" style="color:#059669;"></i> Recent Transactions</div>
                <span class="badge-aw badge-green">{{ count($recentTransactions) }} Records</span>
            </div>
            <div class="aw-card-body p-0">
                <div class="table-responsive-modern">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Method</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $txn)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;">{{ $txn->patient ? $txn->patient->first_name . ' ' . $txn->patient->last_name : 'Unknown' }}</div>
                                        <div style="font-size:11px; color:var(--text-muted);">ID: {{ $txn->patient ? $txn->patient->patient_id : 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($txn->bill_date)->format('d M Y') }}</div>
                                        <div style="font-size:11px; color:var(--text-muted);">{{ \Carbon\Carbon::parse($txn->created_at)->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-aw {{ str_contains(strtolower($txn->payment_method), 'cash') ? 'badge-green' : 'badge-blue' }}">
                                            {{ $txn->payment_method }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div style="font-weight:700;">₹{{ number_format($txn->net_amount, 2) }}</div>
                                        @if($txn->balance_due > 0)
                                            <div style="font-size:11px; color:#ef4444; font-weight:500;">Due: ₹{{ number_format($txn->balance_due, 2) }}</div>
                                        @else
                                            <div style="font-size:11px; color:#10b981; font-weight:500;">Paid</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center" style="padding:48px; color:var(--text-muted);">
                                        <i class="fa fa-folder-open" style="font-size:40px; display:block; margin-bottom:12px; opacity:0.4;"></i>
                                        <span style="font-size:14px;">No transactions found.</span>
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

<!-- Daily Trend Table -->
<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-chart-bar" style="color:var(--primary);"></i> Daily Income Analysis</div>
    </div>
    <div class="aw-card-body p-0">
        <div class="table-responsive-modern">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Billing Date</th>
                        <th class="text-center">Transactions</th>
                        <th class="text-end">Avg. Revenue/Txn</th>
                        <th class="text-end">Daily Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyTrend as $trend)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ \Carbon\Carbon::parse($trend['date'])->format('d M Y') }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">{{ \Carbon\Carbon::parse($trend['date'])->format('l') }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge-aw badge-blue">{{ $trend['transactions'] }} txns</span>
                            </td>
                            <td class="text-end">
                                <span style="font-weight:500; color:var(--primary);">₹{{ number_format($trend['average'], 2) }}</span>
                            </td>
                            <td class="text-end">
                                <div style="font-weight:700; color:#10b981;">₹{{ number_format($trend['income'], 2) }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center" style="padding:48px; color:var(--text-muted);">
                                <i class="fa fa-chart-line" style="font-size:40px; display:block; margin-bottom:12px; opacity:0.4;"></i>
                                <span style="font-size:14px;">No data found for the selected range.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($dailyTrend) > 0)
                <tfoot>
                    <tr style="background:var(--primary-light); font-weight:700; color:#000000;">
                        <td style="padding:14px 16px; border-bottom: none;">TOTAL PERIOD INCOME</td>
                        <td class="text-center" style="padding:14px 16px; border-bottom: none;">{{ $totalTransactions }} txns</td>
                        <td class="text-end" style="padding:14px 16px; border-bottom: none; font-weight:500; color:var(--text-muted); font-size:12px;">Combined Avg: ₹{{ number_format($averageTransaction, 2) }}</td>
                        <td class="text-end" style="padding:14px 16px; border-bottom: none; font-size:16px; font-weight:800; color:#10b981;">₹{{ number_format($totalIncome, 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        #awlab-header, #awlab-sidebar, #awlab-footer, form, .btn-aw-primary, .btn-aw-outline { display: none !important; }
        #awlab-main { margin: 0 !important; padding: 0 !important; }
        .aw-card { border: none !important; box-shadow: none !important; margin-bottom: 20px !important; }
        .table-responsive { overflow: visible !important; }
        body { background: white !important; }
    }
</style>
@endpush
@endsection

