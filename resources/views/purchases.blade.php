@extends('layouts.app')
@section('title', ' | Purchase Receipts')
@section('page-title', 'Purchase Receipts')

@push('styles')
<style>
    /* Action buttons styling */
    .action-buttons {
        display: inline-flex;
        gap: 6px;
    }

    .btn-action-circle {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 13px;
        text-decoration: none;
    }

    .btn-action-circle:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
        transform: translateY(-1px);
    }

    .btn-action-circle.btn-view:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: #eff6ff;
    }
</style>
@endpush

@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-file-invoice-dollar"></i></div>
        <div>
            <div>Purchase Receipts</div>
            <div style="font-size:13px; font-weight:400; color:var(--text-muted); margin-top:2px;">Track inventory purchases and vendor receipts</div>
        </div>
    </div>
    <div class="page-actions-aw">
        <button class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-purchase">
            <i class="fa fa-plus"></i> New Purchase
        </button>
    </div>
</div>

<div class="aw-card">
    <div class="aw-card-header d-flex justify-content-between align-items-center">
        <div class="aw-card-title mb-0"><i class="fa fa-file-invoice-dollar" style="color:var(--primary);"></i> Purchase Receipts</div>
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ $receipts->count() }} total receipts</span>
            <div style="position:relative;">
                <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                <input type="text" id="purchase-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search receipts..." autocomplete="off">
            </div>
        </div>
    </div>
    <div class="aw-card-body p-0">
        <div class="table-responsive-modern md-dt-wrap">
            <table class="table-modern" id="purchase-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Receipt No</th>
                        <th>Vendor Name</th>
                        <th>Items Bought</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-center" width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $receipt)
                        <tr>
                            <td data-label="Date" style="font-weight:600;">
                                {{ \Carbon\Carbon::parse($receipt->purchase_date)->format('d M Y') }}
                            </td>
                            <td data-label="Receipt No">
                                <span class="badge-aw badge-blue">{{ $receipt->receipt_no }}</span>
                            </td>
                            <td data-label="Vendor Name">
                                {{ $receipt->vendor_name ?: 'Unknown Vendor' }}
                            </td>
                            <td data-label="Items Bought">
                                <span style="font-size:13px; color:var(--text-muted);">
                                    {{ $receipt->items->count() }} items ({{ $receipt->items->sum('quantity') }} qty)
                                </span>
                            </td>
                            <td data-label="Total Amount" class="text-end" style="font-weight:700; color:#16a34a;">
                                ₹{{ number_format($receipt->total_amount, 2) }}
                            </td>
                            <td data-label="Actions" class="text-center">
                                <div class="action-buttons justify-content-center">
                                    <button class="btn-action-circle btn-view" data-bs-toggle="modal" data-bs-target="#modal-view-purchase-{{ $receipt->id }}" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- View Purchase Modal -->
                        <div class="modal fade modal-aw" id="modal-view-purchase-{{ $receipt->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Receipt Details: {{ $receipt->receipt_no }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-4">
                                            <div class="col-sm-6">
                                                <div style="font-size:12px; color:var(--text-muted);">Vendor Name</div>
                                                <div style="font-weight:600; font-size:16px;">{{ $receipt->vendor_name ?: 'N/A' }}</div>
                                            </div>
                                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                                <div style="font-size:12px; color:var(--text-muted);">Purchase Date</div>
                                                <div style="font-weight:600; font-size:16px;">{{ \Carbon\Carbon::parse($receipt->purchase_date)->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                        
                                        @if($receipt->notes)
                                        <div class="mb-4 p-3" style="background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
                                            <div style="font-size:12px; font-weight:600; margin-bottom:5px;">Notes</div>
                                            <div style="font-size:14px;">{{ $receipt->notes }}</div>
                                        </div>
                                        @endif

                                        <table class="table-modern" style="box-shadow:none; border:1px solid #e2e8f0;">
                                            <thead>
                                                <tr style="background:#f1f5f9;">
                                                    <th>Product</th>
                                                    <th class="text-end">Unit Price</th>
                                                    <th class="text-end">Qty</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($receipt->items as $item)
                                                <tr>
                                                    <td>{{ $item->product ? $item->product->name : 'Deleted Product' }}</td>
                                                    <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                                                    <td class="text-end">{{ $item->quantity }}</td>
                                                    <td class="text-end" style="font-weight:600;">₹{{ number_format($item->total_price, 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr style="background:#f8fafc;">
                                                    <td colspan="3" class="text-end" style="font-weight:700;">Grand Total:</td>
                                                    <td class="text-end" style="font-weight:700; color:#16a34a; font-size:16px;">₹{{ number_format($receipt->total_amount, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Purchase Modal -->
<div class="modal fade modal-aw" id="modal-add-purchase" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Purchase Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background:#f8fafc;">
                <form id="add-purchase-form" method="POST" action="{{ route('purchases.store') }}">
                    @csrf
                    <div class="row">
                        <!-- Left Side: Receipt Details -->
                        <div class="col-lg-4 mb-4 mb-lg-0">
                            <div class="aw-card h-100" style="margin:0;">
                                <div class="aw-card-body">
                                    <h6 class="mb-3" style="font-weight:700; color:var(--primary);">Receipt Info</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label-aw">Vendor / Supplier Name</label>
                                        <input type="text" class="form-control-aw" name="vendor_name" placeholder="Enter vendor name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-aw">Purchase Date *</label>
                                        <input type="date" class="form-control-aw" name="purchase_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-aw">Notes / References</label>
                                        <textarea class="form-control-aw" name="notes" rows="3" placeholder="Invoice number, payment method, etc."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side: Items -->
                        <div class="col-lg-8">
                            <div class="aw-card h-100" style="margin:0;">
                                <div class="aw-card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 style="font-weight:700; color:var(--primary); margin:0;">Receipt Items</h6>
                                        <button type="button" class="btn-aw-outline btn-sm" onclick="addPurchaseRow()">
                                            <i class="fa fa-plus"></i> Add Row
                                        </button>
                                    </div>
                                    
                                    <div class="table-responsive" style="overflow:visible;">
                                        <table class="table align-middle" id="purchase-items-table">
                                            <thead>
                                                <tr>
                                                    <th style="min-width:200px;">Product *</th>
                                                    <th width="120">Unit Price *</th>
                                                    <th width="100">Qty *</th>
                                                    <th width="120" class="text-end">Total</th>
                                                    <th width="50"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="purchase-items-body">
                                                <!-- Rows will be added here -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end" style="font-weight:700;">Grand Total:</td>
                                                    <td class="text-end" style="font-weight:700; color:#16a34a; font-size:18px;" id="purchase-grand-total">₹0.00</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    
                                    <!-- Hidden input to store JSON string of items for the controller -->
                                    <input type="hidden" name="items" id="purchase-items-json">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="background:#fff;">
                <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-aw-primary" id="btn-save-purchase" onclick="submitPurchase()">Save Receipt & Update Stock</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if(!$.fn.DataTable.isDataTable('#purchase-table')) {
            var pTable = $('#purchase-table').DataTable({
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "info": false,
                "ordering": false,
                "autoWidth": false,
                "language": {
                    "emptyTable": `<div class="text-center py-5 text-muted">
                        <i class="fa fa-file-invoice" style="font-size:30px; opacity:0.4; display:block; margin-bottom:10px;"></i>
                        No purchase receipts found.
                    </div>`,
                    "zeroRecords": "No matching receipts found"
                },
                "dom": 'rt<"d-flex justify-content-between align-items-center mt-3"<"text-muted fs-13"i><"md-pagination"p>>'
            });

            $('#purchase-search').on('keyup', function() {
                pTable.search(this.value).draw();
            });
        }
    });

    let productsList = @json($products);
    
    function addPurchaseRow() {
        let productOptions = '<option value="">Select Product...</option>';
        productsList.forEach(p => {
            productOptions += `<option value="${p.id}" data-price="${p.unit_price}">${p.name} (Stock: ${p.stock_quantity})</option>`;
        });
        
        let tr = `
            <tr class="purchase-item-row">
                <td>
                    <select class="form-select product-select" required onchange="onProductSelect(this)">
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control item-price" required oninput="calculateTotals()" placeholder="0.00">
                </td>
                <td>
                    <input type="number" class="form-control item-qty" value="1" min="1" required oninput="calculateTotals()">
                </td>
                <td class="text-end item-total-text" style="font-weight:600;">₹0.00</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-light text-danger" onclick="$(this).closest('tr').remove(); calculateTotals();">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#purchase-items-body').append(tr);
    }
    
    function onProductSelect(selectElement) {
        let selectedOpt = $(selectElement).find('option:selected');
        let price = selectedOpt.data('price');
        let row = $(selectElement).closest('tr');
        if(price !== undefined) {
            row.find('.item-price').val(price);
        }
        calculateTotals();
    }
    
    function calculateTotals() {
        let grandTotal = 0;
        $('.purchase-item-row').each(function() {
            let price = parseFloat($(this).find('.item-price').val()) || 0;
            let qty = parseInt($(this).find('.item-qty').val()) || 0;
            let total = price * qty;
            grandTotal += total;
            $(this).find('.item-total-text').text('₹' + total.toFixed(2));
        });
        $('#purchase-grand-total').text('₹' + grandTotal.toFixed(2));
    }
    
    function submitPurchase() {
        let items = [];
        let isValid = true;
        
        $('.purchase-item-row').each(function() {
            let productId = $(this).find('.product-select').val();
            let price = $(this).find('.item-price').val();
            let qty = $(this).find('.item-qty').val();
            
            if(!productId || !price || !qty || qty <= 0) {
                isValid = false;
            } else {
                items.push({
                    product_id: productId,
                    unit_price: parseFloat(price),
                    quantity: parseInt(qty)
                });
            }
        });
        
        if(items.length === 0) {
            showToast('Please add at least one valid product item.', 'error');
            return;
        }
        
        if(!isValid) {
            showToast('Please completely fill all added item rows.', 'error');
            return;
        }
        
        $('#purchase-items-json').val(JSON.stringify(items));
        
        let form = $('#add-purchase-form');
        if(!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }
        
        let btn = $('#btn-save-purchase');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                showToast(res.success, 'success');
                setTimeout(() => window.location.reload(), 800);
            },
            error: function(err) {
                btn.prop('disabled', false).html('Save Receipt');
                showToast(err.responseJSON?.message || err.responseJSON?.error || 'Error occurred', 'error');
            }
        });
    }

    // Add initial row when modal opens if empty
    $('#modal-add-purchase').on('shown.bs.modal', function () {
        if($('.purchase-item-row').length === 0) {
            addPurchaseRow();
        }
    });
</script>
@endpush
