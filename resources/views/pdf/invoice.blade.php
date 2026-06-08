<style>
    .invoice-container {
        font-family: 'Courier New', Courier, monospace;
        font-size: 14px;
        color: #000;
        padding: 20px;
        background: #fff;
    }
    .invoice-container .text-center { text-align: center; }
    .invoice-container table { width: 100%; border-collapse: collapse; }
    .invoice-container .header-info td { padding: 4px 0; }
    .invoice-container .divider { 
        border-top: 1px dashed #000; 
        border-bottom: 1px dashed #000; 
        margin: 10px 0; 
    }
    .invoice-container .items-table th, .invoice-container .items-table td { padding: 4px 0; text-align: left; }
    .invoice-container .items-table th.price, .invoice-container .items-table td.price { text-align: right; }
    .invoice-container .totals-table { width: 100%; margin-top: 10px; }
    .invoice-container .totals-table td { padding: 4px 0; }
    .invoice-container .label-col { width: 120px; }
    .invoice-container .sep-col { width: 15px; }
</style>

<div class="invoice-container">
    <div class="text-center" style="font-weight: bold; font-size: 16px; margin-bottom: 20px;">
        TAX INVOICE
    </div>

    <table class="header-info">
        <tr>
            <td width="15%">Bill No</td>
            <td width="2%">:</td>
            <td width="33%">{{ $patient->patient_id ?? 'AWL1180' }}</td>
            <td width="15%">Patient Name</td>
            <td width="2%">:</td>
            <td width="33%">{{ strtoupper($patient->first_name ?? 'ASHIK') }} {{ strtoupper($patient->last_name ?? '') }}</td>
        </tr>
        <tr>
            <td>Bill Date</td>
            <td>:</td>
            <td>{{ isset($patient->created_at) ? \Carbon\Carbon::parse($patient->created_at)->format('d-M-Y - h:i:s A') : '16-May-2024 - 10:18:24 AM' }}</td>
            <td>Age</td>
            <td>:</td>
            <td>{{ $patient->age ?? '25' }} {{ $patient->age_type ?? 'Years' }} &nbsp;&nbsp; Sex : {{ strtoupper($patient->gender ?? 'MALE') }}</td>
        </tr>
        <tr>
            <td>Referrer</td>
            <td>:</td>
            <td>{{ $patient->reference_dr ?? 'Self' }}</td>
            <td>Phone No</td>
            <td>:</td>
            <td>{{ $patient->phone ?? '9747683938' }}</td>
        </tr>
    </table>

    <div class="divider">
        <table class="items-table" style="margin: 0;">
            <thead>
                <tr>
                    <th width="10%">S.No</th>
                    <th width="70%">Description</th>
                    <th width="20%" class="price">Price</th>
                </tr>
            </thead>
        </table>
    </div>

    <table class="items-table" style="margin-bottom: 10px;">
        <tbody>
            @if(isset($patient) && isset($patient->appointments) && count($patient->appointments) > 0)
                @foreach($patient->appointments as $index => $item)
                <tr>
                    <td width="10%">{{ $index + 1 }}</td>
                    <td width="70%">{{ strtoupper($item->test_name) }}</td>
                    <td width="20%" class="price">{{ number_format($item->test_price, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td width="10%">1</td>
                    <td width="70%">VDRL RPR CARD TEST</td>
                    <td width="20%" class="price">150.00</td>
                </tr>
                <tr>
                    <td width="10%">2</td>
                    <td width="70%">URINE ROUTINE</td>
                    <td width="20%" class="price">100.00</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="totals-table">
        <tr>
            <td class="label-col">Total Items</td>
            <td class="sep-col">:</td>
            <td>{{ isset($patient->appointments) ? count($patient->appointments) : 2 }}</td>
        </tr>
        <tr>
            <td>Amount</td>
            <td>:</td>
            <td>{{ number_format($patient->total_amount ?? 250.00, 2) }}</td>
        </tr>
        <tr>
            <td>Discount</td>
            <td>:</td>
            <td>{{ number_format($patient->discount ?? 0.00, 2) }}</td>
        </tr>
        <tr>
            <td>Net Amount</td>
            <td>:</td>
            <td>{{ number_format(($patient->total_amount ?? 250.00) - ($patient->discount ?? 0.00), 2) }}</td>
        </tr>
        <tr>
            <td>Paid Amount</td>
            <td>:</td>
            <td>{{ number_format(($patient->total_amount ?? 250.00) - ($patient->discount ?? 0.00), 2) }}</td>
        </tr>
        <tr>
            <td>Balance Amount</td>
            <td>:</td>
            <td>{{ number_format($patient->balance ?? 0.00, 2) }}</td>
        </tr>
        <tr>
            <td>Payment Mode</td>
            <td>:</td>
            <td>cash</td>
        </tr>
    </table>
</div>
