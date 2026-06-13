@extends('layouts.app')
@section('title', ' | Daily Collection')
@section('page-title', 'Daily Collection')

@section('content')
<div class="page-header-aw d-print-none">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-cash-register"></i></div>
        <div>
            <div>Daily Collection Summary</div>
            <div style="font-size:13px; font-weight:400; color:var(--text-muted); margin-top:2px;">End-of-day tally for all cash, card, and UPI collections</div>
        </div>
    </div>
</div>

<!-- Filter & Actions Box -->
<div class="aw-card mb-4 d-print-none">
    <div class="aw-card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <form action="{{ route('daily-collection') }}" method="GET" class="d-flex align-items-end gap-3 flex-wrap">
            <div>
                <label for="collection_date" class="form-label-aw">Select Date</label>
                <input type="date" class="form-control-aw" name="date" value="{{ request('date', date('Y-m-d')) }}" id="collection_date" required>
            </div>
            <div>
                <button type="submit" class="btn-aw-primary"><i class="fa fa-search"></i> Fetch Collection</button>
            </div>
        </form>
        
        <div>
            <button onclick="window.print()" class="btn-aw-outline" style="border-color:#16a34a; color:#16a34a;">
                <i class="fa fa-print"></i> Print EOD Report
            </button>
        </div>
    </div>
</div>

<!-- Printable Area -->
<div id="print-area">
    <!-- Print Header -->
    <div class="d-none d-print-block mb-4 text-center pb-3" style="border-bottom: 2px dashed #ccc;">
        <h2 style="margin:0; font-family:'Outfit', sans-serif; font-weight:800;">{{ config('app.name', 'SUHAIM SOFT LAB') }}</h2>
        <div style="font-size:14px; margin-top:5px;">End of Day Collection Report</div>
        <div style="font-size:14px; font-weight:bold; margin-top:5px;">Date: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</div>
        <div style="font-size:12px; margin-top:5px;">Printed On: {{ date('d M Y, h:i A') }}</div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-6 col-md-6 col-12">
            <div class="stat-card-new h-100">
                <div class="stat-icon-circle stat-icon-green">
                    <i class="fa fa-rupee-sign"></i>
                </div>
                <div class="stat-text">
                    <div class="stat-num">₹{{ number_format($totalCollection, 2) }}</div>
                    <div class="stat-lbl">Total Collection for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12">
            <div class="stat-card-new h-100">
                <div class="stat-icon-circle stat-icon-blue">
                    <i class="fa fa-receipt"></i>
                </div>
                <div class="stat-text">
                    <div class="stat-num">{{ $totalTransactions }}</div>
                    <div class="stat-lbl">Total Transactions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection Breakdown -->
    <div class="aw-card mb-4">
        <div class="aw-card-header d-print-none">
            <div class="aw-card-title"><i class="fa fa-pie-chart" style="color:var(--primary);"></i> Collection Breakdown</div>
        </div>
        <div class="aw-card-body">
            @if($methods->count() > 0)
                <div class="row g-3">
                    @foreach($methods as $method)
                    <div class="col-md-4 col-sm-6 col-12">
                        <div style="padding:15px; border:1px solid var(--border-color); border-radius:10px; background:#f8fafc; text-align:center;">
                            <div style="font-size:14px; color:var(--text-muted); font-weight:600; text-transform:uppercase;">
                                {{ $method['method'] }}
                            </div>
                            <div style="font-size:24px; font-weight:700; color:var(--text-dark); margin:8px 0;">
                                ₹{{ number_format($method['amount'], 2) }}
                            </div>
                            <div style="font-size:12px; color:var(--text-muted);">
                                {{ $method['count'] }} Transactions
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4" style="color:var(--text-muted);">
                    No collection data found for this date.
                </div>
            @endif
        </div>
    </div>

    <!-- Individual Transactions -->
    <div class="aw-card">
        <div class="aw-card-header d-print-none">
            <div class="aw-card-title"><i class="fa fa-list" style="color:#059669;"></i> Transaction Ledger</div>
        </div>
        <div class="aw-card-body p-0">
            <div class="table-responsive-modern">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient Name</th>
                            <th>Patient ID</th>
                            <th>Method</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td data-label="Time">
                                    {{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}
                                </td>
                                <td data-label="Patient Name">
                                    <div style="font-weight:600;">{{ $payment->patient ? $payment->patient->first_name . ' ' . $payment->patient->last_name : 'Unknown Patient' }}</div>
                                </td>
                                <td data-label="Patient ID">
                                    {{ $payment->patient ? $payment->patient->patient_id : 'N/A' }}
                                </td>
                                <td data-label="Method">
                                    <span class="badge-aw badge-blue">{{ $payment->payment_method ?: 'N/A' }}</span>
                                </td>
                                <td data-label="Amount" class="text-end" style="font-weight:700;">
                                    ₹{{ number_format($payment->net_amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa fa-box-open" style="font-size:30px; opacity:0.4; display:block; margin-bottom:10px;"></i>
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($payments->count() > 0)
                    <tfoot>
                        <tr style="background:#f1f5f9; font-weight:700;">
                            <td colspan="4" class="text-end py-3">TOTAL COLLECTION:</td>
                            <td class="text-end py-3" style="font-size:18px; color:#16a34a;">₹{{ number_format($totalCollection, 2) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
    
    <!-- Print Footer -->
    <div class="d-none d-print-block mt-5 pt-3" style="border-top: 1px solid #ccc; text-align:center;">
        <div style="font-weight:bold;">Authorized Signature</div>
        <div style="margin-top:40px; font-size:12px; color:#666;">This is a system generated report.</div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        @page { size: A4 portrait; margin: 15mm; }
        body { background: #fff !important; color: #000 !important; }
        #awlab-header, #awlab-sidebar, .d-print-none { display: none !important; }
        #awlab-main { margin: 0 !important; padding: 0 !important; }
        .aw-card { border: none !important; box-shadow: none !important; margin: 0 !important; }
        .aw-card-body { padding: 0 !important; }
        .table-responsive-modern { border: none !important; box-shadow: none !important; overflow: visible !important; }
        table { width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #ddd !important; padding: 8px !important; }
        th { background: #f8f9fa !important; -webkit-print-color-adjust: exact; color: #000 !important; }
        .badge-aw { border: 1px solid #ccc !important; color: #000 !important; background: transparent !important; }
        .stat-card-new { border: 1px solid #ccc !important; box-shadow: none !important; padding: 15px !important; margin-bottom: 15px !important; }
        .stat-num { color: #000 !important; }
        .col-md-4 { width: 33.333% !important; float: left !important; }
        .row { display: block !important; overflow: hidden !important; }
    }
</style>
@endpush
@endsection
