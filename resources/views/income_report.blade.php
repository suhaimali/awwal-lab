@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title text-dark fw-700">Financial Insights</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Income Report</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Filter Row -->
            <div class="row mb-30">
                <div class="col-12">
                    <div class="box mb-0 border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                        <div class="box-body py-20 bg-white">
                            <form action="{{ route('income-report') }}" method="GET" class="row align-items-end g-3">
                                <input type="hidden" name="auth" value="safwan">
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-600 text-fade small uppercase">Start Date</label>
                                    <div class="input-group border rounded-pill overflow-hidden">
                                        <span class="input-group-text bg-white border-0"><i class="fa fa-calendar text-primary"></i></span>
                                        <input type="date" class="form-control border-0" name="start_date" value="{{ request('start_date', date('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-600 text-fade small uppercase">End Date</label>
                                    <div class="input-group border rounded-pill overflow-hidden">
                                        <span class="input-group-text bg-white border-0"><i class="fa fa-calendar text-danger"></i></span>
                                        <input type="date" class="form-control border-0" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary rounded-pill px-25 flex-fill fw-600"><i class="fa fa-filter me-2"></i> Apply Filter</button>
                                        <a href="{{ route('income-report') }}?auth=safwan" class="btn btn-light rounded-pill px-25 flex-fill fw-600 border"><i class="fa fa-refresh me-2"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Premium Stats Row -->
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box overflow-hidden pull-up shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(135deg, #00b09b, #96c93d);">
                        <div class="box-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="text-white mb-0 opacity-80 fw-500">Total Income</h5>
                                    <h2 class="text-white mb-0 fw-700 mt-5">₹{{ number_format($totalIncome, 2) }}</h2>
                                </div>
                                <div class="bg-white-20 rounded-circle p-15">
                                    <i class="fa fa-money text-white fs-30"></i>
                                </div>
                            </div>
                            <div class="mt-15 text-white-50 small">
                                <i class="fa fa-info-circle me-1"></i> Net earnings in period
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box overflow-hidden pull-up shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(135deg, #4facfe, #00f2fe);">
                        <div class="box-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="text-white mb-0 opacity-80 fw-500">Transactions</h5>
                                    <h2 class="text-white mb-0 fw-700 mt-5">{{ $totalTransactions }}</h2>
                                </div>
                                <div class="bg-white-20 rounded-circle p-15">
                                    <i class="fa fa-shopping-cart text-white fs-30"></i>
                                </div>
                            </div>
                            <div class="mt-15 text-white-50 small">
                                <i class="fa fa-check-circle me-1"></i> Completed payments
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box overflow-hidden pull-up shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(135deg, #667eea, #764ba2);">
                        <div class="box-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="text-white mb-0 opacity-80 fw-500">Avg. Basket</h5>
                                    <h2 class="text-white mb-0 fw-700 mt-5">₹{{ number_format($averageTransaction, 2) }}</h2>
                                </div>
                                <div class="bg-white-20 rounded-circle p-15">
                                    <i class="fa fa-line-chart text-white fs-30"></i>
                                </div>
                            </div>
                            <div class="mt-15 text-white-50 small">
                                <i class="fa fa-arrow-up me-1"></i> Value per transaction
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box overflow-hidden pull-up shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <div class="box-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="text-white mb-0 opacity-80 fw-500">Active Days</h5>
                                    <h2 class="text-white mb-0 fw-700 mt-5">{{ count($dailyTrend) }} Days</h2>
                                </div>
                                <div class="bg-white-20 rounded-circle p-15">
                                    <i class="fa fa-calendar-check-o text-white fs-30"></i>
                                </div>
                            </div>
                            <div class="mt-15 text-white-50 small">
                                <i class="fa fa-clock-o me-1"></i> Analysis span
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts / Bars Row -->
            <div class="row align-items-stretch">
                <div class="col-xl-5 col-12 d-flex">
                    <div class="box border-0 shadow-sm flex-fill" style="border-radius: 15px;">
                        <div class="box-header with-border">
                            <h4 class="box-title fw-600">Payment Methods Breakdown</h4>
                        </div>
                        <div class="box-body">
                            @if(count($methods) > 0)
                                @php
                                    $colors = ['#00b09b', '#4facfe', '#764ba2', '#f5576c', '#f9d423'];
                                    $colorIndex = 0;
                                @endphp
                                @foreach($methods as $method)
                                    @php
                                        $percentage = $totalIncome > 0 ? ($method['amount'] / $totalIncome) * 100 : 0;
                                        $color = $colors[$colorIndex % count($colors)];
                                        $colorIndex++;
                                        
                                        $icon = 'fa-credit-card';
                                        $methodName = strtolower($method['method']);
                                        if(str_contains($methodName, 'cash')) $icon = 'fa-money';
                                        elseif(str_contains($methodName, 'upi') || str_contains($methodName, 'online')) $icon = 'fa-mobile';
                                        elseif(str_contains($methodName, 'card')) $icon = 'fa-credit-card-alt';
                                    @endphp
                                    <div class="mb-25">
                                        <div class="d-flex align-items-center justify-content-between mb-10">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-10 me-15">
                                                    <i class="fa {{ $icon }} text-fade fs-18"></i>
                                                </div>
                                                <div>
                                                    <h5 class="my-0 fw-600">{{ $method['method'] }}</h5>
                                                    <span class="text-fade small">{{ $method['count'] }} transactions</span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="my-0 fw-700">₹{{ number_format($method['amount'], 2) }}</h5>
                                                <span class="badge badge-primary-light small">{{ number_format($percentage, 1) }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress progress-sm mb-0 rounded-pill bg-light" style="height: 6px;">
                                            <div class="progress-bar rounded-pill" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $percentage }}%; background-color: {{ $color }};">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-50">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-15" style="width: 80px; height: 80px;">
                                        <i class="fa fa-credit-card-alt fa-2x text-fade"></i>
                                    </div>
                                    <p class="text-fade fw-500">No payment method data available.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-7 col-12 d-flex">
                    <div class="box border-0 shadow-sm flex-fill" style="border-radius: 15px;">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title fw-600">Recent Transactions</h4>
                            <span class="badge badge-primary-light rounded-pill px-15">{{ count($recentTransactions) }} Records</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 responsive-table">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="ps-20 border-0">Patient Info</th>
                                            <th class="border-0">Date</th>
                                            <th class="border-0">Method</th>
                                            <th class="text-end pe-20 border-0">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentTransactions as $txn)
                                            <tr>
                                                <td class="ps-20" data-label="Patient">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary-light rounded-circle me-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                            <span class="text-primary fw-600 small">{{ $txn->patient ? substr($txn->patient->first_name, 0, 1) : '?' }}</span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-600 text-dark">{{ $txn->patient ? $txn->patient->first_name . ' ' . $txn->patient->last_name : 'Unknown' }}</div>
                                                            <div class="text-fade small">ID: {{ $txn->patient ? $txn->patient->patient_id : 'N/A' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td data-label="Date">
                                                    <div class="fw-500">{{ \Carbon\Carbon::parse($txn->bill_date)->format('d M Y') }}</div>
                                                    <div class="text-fade small">{{ \Carbon\Carbon::parse($txn->created_at)->format('h:i A') }}</div>
                                                </td>
                                                <td data-label="Method">
                                                    <span class="badge badge-{{ str_contains(strtolower($txn->payment_method), 'cash') ? 'success' : 'info' }}-light rounded-pill px-10">
                                                        {{ $txn->payment_method }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-20" data-label="Amount">
                                                    <div class="fw-700 text-dark">₹{{ number_format($txn->net_amount, 2) }}</div>
                                                    @if($txn->balance_due > 0)
                                                        <div class="text-danger small">Due: ₹{{ number_format($txn->balance_due, 2) }}</div>
                                                    @else
                                                        <div class="text-success small">Paid</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-50">
                                                    <div class="text-fade">
                                                        <i class="fa fa-folder-open-o fa-2x mb-10 d-block"></i>
                                                        No recent transactions found.
                                                    </div>
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
            <div class="row">
                <div class="col-12">
                    <div class="box border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="box-header with-border bg-dark rounded-top-15">
                            <h4 class="box-title text-white fw-600">Daily Income Analysis</h4>
                            <div class="box-controls pull-right">
                                <span class="badge badge-light-50 text-white">Periodic Breakdown</span>
                            </div>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 responsive-table">
                                    <thead>
                                        <tr class="bg-light-20">
                                            <th class="ps-20">Billing Date</th>
                                            <th class="text-center">Transactions</th>
                                            <th class="text-end">Avg. Revenue/Txn</th>
                                            <th class="text-end pe-20">Daily Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dailyTrend as $trend)
                                            <tr>
                                                <td class="ps-20" data-label="Date">
                                                    <div class="fw-600 text-dark">{{ \Carbon\Carbon::parse($trend['date'])->format('d M Y') }}</div>
                                                    <div class="text-fade small">{{ \Carbon\Carbon::parse($trend['date'])->format('l') }}</div>
                                                </td>
                                                <td class="text-center" data-label="Count">
                                                    <span class="badge badge-secondary-light px-15 rounded-pill">{{ $trend['transactions'] }} txns</span>
                                                </td>
                                                <td class="text-end" data-label="Average">
                                                    <div class="text-info fw-500">₹{{ number_format($trend['average'], 2) }}</div>
                                                </td>
                                                <td class="text-end pe-20" data-label="Daily Total">
                                                    <div class="fw-700 text-success fs-16">₹{{ number_format($trend['income'], 2) }}</div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-80">
                                                    <div class="text-fade">
                                                        <i class="fa fa-line-chart fa-3x mb-15"></i>
                                                        <p class="fs-18">No data found for the selected range.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if(count($dailyTrend) > 0)
                                    <tfoot class="bg-light">
                                        <tr>
                                            <td class="ps-20 fw-700 text-dark">TOTAL PERIOD INCOME</td>
                                            <td class="text-center fw-700 text-dark">{{ $totalTransactions }}</td>
                                            <td class="text-end text-fade small">Combined Avg: ₹{{ number_format($averageTransaction, 2) }}</td>
                                            <td class="text-end pe-20">
                                                <div class="fw-800 text-success fs-20">₹{{ number_format($totalIncome, 2) }}</div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                /* Premium UI Enhancements */
                .pull-up { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
                .pull-up:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
                .bg-white-20 { background-color: rgba(255,255,255,0.2); }
                .rounded-top-15 { border-top-left-radius: 15px !important; border-top-right-radius: 15px !important; }
                
                /* Responsive Table Premium Styling */
                @media (max-width: 767px) {
                    .responsive-table thead { display: none; }
                    .responsive-table tbody tr { 
                        display: block; 
                        border-bottom: 1px solid #eee; 
                        padding: 15px 10px;
                        margin-bottom: 10px;
                        background: #fff;
                        border-radius: 10px;
                    }
                    .responsive-table td { 
                        display: flex; 
                        justify-content: space-between; 
                        align-items: center;
                        border: none !important; 
                        padding: 8px 10px !important; 
                    }
                    .responsive-table td::before { 
                        content: attr(data-label); 
                        font-weight: 700; 
                        color: #888;
                        font-size: 12px;
                        text-transform: uppercase;
                    }
                    .responsive-table tfoot td { 
                        display: flex; 
                        justify-content: space-between; 
                        border: none !important; 
                        padding: 15px !important; 
                    }
                    .responsive-table tfoot td::before { content: "Summary"; font-weight: 700; }
                    .ps-20 { padding-left: 10px !important; }
                    .pe-20 { padding-right: 10px !important; }
                }

                @media print {
                    .main-header, .main-sidebar, .content-header, .box-header .box-controls, form, .btn { display: none !important; }
                    .content-wrapper { margin-left: 0 !important; padding: 0 !important; }
                    .box { border: 1px solid #eee !important; box-shadow: none !important; margin-bottom: 20px !important; }
                    .table-responsive { overflow: visible !important; }
                    body { background: white !important; }
                    .box-header { background: #f8f9fa !important; border-bottom: 1px solid #eee !important; }
                    .box-header .box-title { color: #000 !important; }
                }
            </style>
        </section>
    </div>
</div>
@endsection
