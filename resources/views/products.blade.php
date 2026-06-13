@extends('layouts.app')
@section('title', ' | Products')
@section('page-title', 'Products Management')

@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-boxes"></i></div>
        <div>
            <div>Products & Inventory</div>
            <div style="font-size:13px; font-weight:400; color:var(--text-muted); margin-top:2px;">Manage lab supplies, items, and current stock</div>
        </div>
    </div>
    <div class="page-actions-aw">
        <button class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-product">
            <i class="fa fa-plus"></i> Add Product
        </button>
    </div>
</div>

<div class="aw-card">
    <div class="aw-card-header d-flex justify-content-between align-items-center">
        <div class="aw-card-title mb-0"><i class="fa fa-boxes" style="color:var(--primary);"></i> Product List</div>
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ $products->count() }} total products</span>
            <div style="position:relative;">
                <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                <input type="text" id="product-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:220px;" placeholder="Search products..." autocomplete="off">
            </div>
        </div>
    </div>
    <div class="aw-card-body p-0">
        <div class="table-responsive-modern md-dt-wrap">
            <table class="table-modern" id="product-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Stock Qty</th>
                        <th class="text-center" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td data-label="Product Name" style="font-weight:600;">{{ $product->name }}</td>
                            <td data-label="Description" style="color:var(--text-muted);">{{ $product->description ?: '-' }}</td>
                            <td data-label="Unit"><span class="badge-aw badge-blue">{{ $product->unit }}</span></td>
                            <td data-label="Unit Price" class="text-end">₹{{ number_format($product->unit_price, 2) }}</td>
                            <td data-label="Stock Qty" class="text-end">
                                @if($product->stock_quantity <= 0)
                                    <span class="badge-aw badge-red">Out of Stock</span>
                                @elseif($product->stock_quantity < 10)
                                    <span style="color:#f59e0b; font-weight:bold;">{{ $product->stock_quantity }} Low</span>
                                @else
                                    <span style="color:#10b981; font-weight:bold;">{{ $product->stock_quantity }}</span>
                                @endif
                            </td>
                            <td data-label="Actions" class="text-center">
                                <div class="action-buttons-center">
                                    <button class="btn-icon" data-bs-toggle="modal" data-bs-target="#modal-edit-product-{{ $product->id }}" title="Edit">
                                        <i class="fa fa-edit text-primary"></i>
                                    </button>
                                    <button class="btn-icon" onclick="deleteProduct({{ $product->id }})" title="Delete">
                                        <i class="fa fa-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Product Modal -->
                        <div class="modal fade modal-aw" id="modal-edit-product-{{ $product->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="product-form" data-id="{{ $product->id }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label-aw">Product Name *</label>
                                                <input type="text" class="form-control-aw" name="name" value="{{ $product->name }}" placeholder="Enter product name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label-aw">Description</label>
                                                <textarea class="form-control-aw" name="description" rows="2" placeholder="Brief description (optional)">{{ $product->description }}</textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label-aw">Unit</label>
                                                    <input type="text" class="form-control-aw" name="unit" value="{{ $product->unit }}" placeholder="e.g. pcs, box, kg">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label-aw">Unit Price</label>
                                                    <input type="number" step="0.01" class="form-control-aw" name="unit_price" value="{{ $product->unit_price }}">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label-aw">Stock Qty</label>
                                                    <input type="number" class="form-control-aw" name="stock_quantity" value="{{ $product->stock_quantity }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-aw-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade modal-aw" id="modal-add-product" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add-product-form" method="POST" action="{{ route('products.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-aw">Product Name *</label>
                        <input type="text" class="form-control-aw" name="name" placeholder="Enter product name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-aw">Description</label>
                        <textarea class="form-control-aw" name="description" rows="2" placeholder="Brief description (optional)"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-aw">Unit</label>
                            <input type="text" class="form-control-aw" name="unit" placeholder="e.g. pcs, box, kg">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-aw">Unit Price</label>
                            <input type="number" step="0.01" class="form-control-aw" name="unit_price" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-aw">Initial Stock</label>
                            <input type="number" class="form-control-aw" name="stock_quantity" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-aw-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#add-product-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let btn = form.find('button[type="submit"]');
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
                btn.prop('disabled', false).html('Save Product');
                showToast(err.responseJSON?.message || 'Error occurred', 'error');
            }
        });
    });

    $('.product-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let id = form.data('id');
        let btn = form.find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: `/products/${id}`,
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                showToast(res.success, 'success');
                setTimeout(() => window.location.reload(), 800);
            },
            error: function(err) {
                btn.prop('disabled', false).html('Save Changes');
                showToast(err.responseJSON?.message || 'Error occurred', 'error');
            }
        });
    });

    function deleteProduct(id) {
        if(confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: `/products/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    showToast(res.success, 'success');
                    setTimeout(() => window.location.reload(), 800);
                },
                error: function(err) {
                    showToast(err.responseJSON?.error || 'Error deleting product', 'error');
                }
            });
        }
    }

    $(document).ready(function() {
        var productTable = $('#product-table').DataTable({
            dom: "<'row mb-3'<'col-sm-12 col-md-6'l>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: false,
            language: {
                lengthMenu: "Show _MENU_ records",
                info: "Showing _START_ to _END_ of _TOTAL_ products",
                infoEmpty: "Showing 0 to 0 of 0 products",
                infoFiltered: "(filtered from _MAX_ total products)",
                emptyTable: "No products found in the database.",
                paginate: {
                    previous: "<i class='fa fa-angle-left'></i>",
                    next: "<i class='fa fa-angle-right'></i>"
                }
            }
        });

        $("#product-search").on("keyup", function() {
            productTable.search($(this).val()).draw();
        });
    });
</script>
@endpush
