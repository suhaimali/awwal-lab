@extends('layouts.app')
@section('title', ' | Payments & Billing')
@section('page-title', 'Payments & Billing')
@section('content')
<div class="page-header-aw">
    <div class="page-title-aw">
        <div class="title-icon"><i class="fa fa-credit-card"></i></div>
        <div>
            <div>Payments &amp; Billing</div>
            <div style="font-size:13px;font-weight:400;color:var(--text-muted);margin-top:2px;">Manage all patient payments</div>
        </div>
    </div>
    <button type="button" class="btn-aw-primary" data-bs-toggle="modal" data-bs-target="#modal-add-payment">
        <i class="fa fa-plus"></i> Add New Payment
    </button>
</div>
<div class="aw-card mb-4">
    <div class="aw-card-header">
        <div class="aw-card-title"><i class="fa fa-credit-card" style="color:var(--primary);"></i> Payment Records</div>
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:12px;color:var(--text-muted);"><i class="fa fa-circle-info me-1"></i>{{ count($payments) }} records</span>
            <div style="position:relative;">
                <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                <input type="text" id="payment-search" style="border:1.5px solid var(--border-color);border-radius:9px;padding:8px 12px 8px 32px;font-size:13px;outline:none;width:210px;" placeholder="Search payments..." autocomplete="off" name="name_1097">
            </div>
        </div>
    </div>
    <div class="aw-card-body" style="padding:0;">
        <div class="table-responsive-modern">
<table class="table table-modern" id="payments-table">
    <thead>
        <tr>
            <th>SL No</th>
            <th>Bill Date</th>
            <th>Patient</th>
            <th>Total</th>
            <th>Discount</th>
            <th>Advance</th>
            <th>Balance</th>
            <th>Method</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $p)
        <tr>
            <td data-label="SL No"><span class="badge-aw badge-blue">#{{ $p->id }}</span></td>
            <td data-label="Bill Date" style="color:var(--text-muted);font-size:12px;">{{ \Carbon\Carbon::parse($p->bill_date)->format('d M Y') }}</td>
            <td data-label="Patient" style="font-weight:600;">{{ optional($p->patient)->first_name }} {{ optional($p->patient)->last_name }}</td>
            <td data-label="Total" style="font-weight:700;color:var(--primary);">₹{{ number_format($p->total_amount ?? 0, 2) }}</td>
            <td data-label="Discount" style="color:#dc2626;">₹{{ number_format($p->discount ?? 0, 2) }}</td>
            <td data-label="Advance">₹{{ number_format($p->advance_paid ?? 0, 2) }}</td>
            <td data-label="Balance" style="font-weight:700;color:#059669;">₹{{ number_format($p->balance_due ?? 0, 2) }}</td>
            <td data-label="Method">
                @php $method = $p->payment_method ?? 'Cash'; @endphp
                <span class="badge-aw {{ $method == 'Cash' ? 'badge-green' : ($method == 'UPI' ? 'badge-purple' : ($method == 'Card' ? 'badge-blue' : 'badge-orange')) }}">
                    <i class="fa {{ $method == 'Cash' ? 'fa-money-bill' : ($method == 'UPI' ? 'fa-mobile' : ($method == 'Card' ? 'fa-credit-card' : 'fa-building-columns')) }} me-1"></i>{{ $method }}
                </span>
            </td>
            <td data-label="Status">
                <span class="badge-aw {{ $p->payment_status == 'Paid' ? 'badge-green' : ($p->payment_status == 'Partial' ? 'badge-orange' : 'badge-red') }}">
                    {{ $p->payment_status }}
                </span>
            </td>
            <td data-label="Actions" class="text-end">
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn-aw-primary btn-aw-sm btn-edit" data-id="{{ $p->id }}" data-bs-toggle="modal" data-bs-target="#modal-edit-payment" title="Edit"><i class="fa fa-pen"></i></button>
                    <button class="btn-aw-danger btn-aw-sm btn-delete" data-id="{{ $p->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete-payment" title="Delete"><i class="fa fa-trash"></i></button>
                </div>
            </td>
        </tr>
        @endforeach
        @if(count($payments) == 0)
        <tr>
            <td colspan="10" class="text-center" style="padding:48px;color:var(--text-muted);">
                <i class="fa fa-credit-card" style="font-size:40px;display:block;margin-bottom:12px;opacity:0.4;"></i>
                <span style="font-size:15px;">No payment records found.</span>
            </td>
        </tr>
        @endif
    </tbody>
</table>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade modal-aw" id="modal-add-payment" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-credit-card me-2"></i>Add New Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-add-payment">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="field_1098" class="form-label">Patient</label>
                <select class="form-select" name="patient_id" required autocomplete="off" id="field_1098">
                  <option value="">-- Select Patient --</option>
                  @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="field_1099" class="form-label">Bill Date</label>
                <input type="date" class="form-control" name="bill_date" value="{{ date('Y-m-d') }}" required autocomplete="off" id="field_1099">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="field_1100" class="form-label">Payment Status</label>
                <select class="form-select" name="payment_status" required autocomplete="off" id="field_1100">
                  <option value="Unpaid">Unpaid</option>
                  <option value="Partial">Partial</option>
                  <option value="Paid">Paid</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="add-total" class="form-label">Total Amount (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input" name="total_amount" id="add-total" required autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="add-discount" class="form-label">Discount (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input" name="discount" id="add-discount" value="0.00" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="add-advance" class="form-label">Advance Paid (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input" name="advance_paid" id="add-advance" value="0.00" autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="field_1101" class="form-label">Payment Method</label>
                <select class="form-select" name="payment_method" required autocomplete="off" id="field_1101">
                  <option value="Cash">Cash</option>
                  <option value="UPI">UPI</option>
                  <option value="Card">Card</option>
                  <option value="Net Banking">Net Banking</option>
                </select>
              </div>
            </div>
          </div>
          <div class="bg-primary-light p-3 rounded mb-3 text-end">
            <h5 class="mb-0 fw-bold">Balance Due: ₹<span id="add-balance">0.00</span></h5>
          </div>
          <div class="form-group">
            <label for="field_1102" class="form-label">Remarks</label>
            <textarea class="form-control" name="remarks" rows="2" autocomplete="off" id="field_1102"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn-aw-primary" id="btn-save-payment"><i class="fa fa-check"></i> Save Payment</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade modal-aw" id="modal-edit-payment" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-edit-payment">
          <input type="hidden" name="id" id="edit-id">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="edit-patient-id" class="form-label">Patient</label>
                <select class="form-select" name="patient_id" id="edit-patient-id" required autocomplete="off">
                  <option value="">-- Select Patient --</option>
                  @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-bill-date" class="form-label">Bill Date</label>
                <input type="date" class="form-control" name="bill_date" id="edit-bill-date" required autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-status" class="form-label">Payment Status</label>
                <select class="form-select" name="payment_status" id="edit-status" required autocomplete="off">
                  <option value="Unpaid">Unpaid</option>
                  <option value="Partial">Partial</option>
                  <option value="Paid">Paid</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-total" class="form-label">Total Amount (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input-edit" name="total_amount" id="edit-total" required autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-discount" class="form-label">Discount (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input-edit" name="discount" id="edit-discount" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-advance" class="form-label">Advance Paid (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input-edit" name="advance_paid" id="edit-advance" autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-method" class="form-label">Payment Method</label>
                <select class="form-select" name="payment_method" id="edit-method" required autocomplete="off">
                  <option value="Cash">Cash</option>
                  <option value="UPI">UPI</option>
                  <option value="Card">Card</option>
                  <option value="Net Banking">Net Banking</option>
                </select>
              </div>
            </div>
          </div>
          <div class="bg-primary-light p-3 rounded mb-3 text-end">
            <h5 class="mb-0 fw-bold">Balance Due: ₹<span id="edit-balance">0.00</span></h5>
          </div>
          <div class="form-group">
            <label for="edit-remarks" class="form-label">Remarks</label>
            <textarea class="form-control" name="remarks" id="edit-remarks" rows="2" autocomplete="off"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn-aw-primary" id="btn-update-payment"><i class="fa fa-check"></i> Update Payment</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade modal-aw" id="modal-delete-payment" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width:400px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-triangle-exclamation me-2"></i>Delete Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="fa fa-warning" style="font-size:48px;color:#dc2626;display:block;margin-bottom:16px;"></i>
        <h5 style="font-weight:700;">Confirm Deletion</h5>
        <p style="color:var(--text-muted);">Are you sure you want to delete this payment record? This action cannot be undone.</p>
        <input type="hidden" id="delete-id" name="name_1103">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-aw-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn-aw-danger" id="btn-confirm-delete">Delete Permanently</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Initialize DataTables for Payments
    var paymentsTable = $('#payments-table').DataTable({
        dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-none'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: false,
        language: {
            lengthMenu: "Show _MENU_ records",
            info: "Showing _START_ to _END_ of _TOTAL_ payments",
            infoEmpty: "Showing 0 to 0 of 0 payments",
            infoFiltered: "(filtered from _MAX_ total payments)",
            paginate: {
                previous: "<i class='fa fa-angle-left'></i>",
                next: "<i class='fa fa-angle-right'></i>"
            }
        }
    });
    $('#payment-search').on('keyup', function() {
        paymentsTable.search($(this).val()).draw();
    });

    // Calculation Logic
    function calculateBalance(context = 'add') {
      let total = parseFloat($(`#${context}-total`).val()) || 0;
      let discount = parseFloat($(`#${context}-discount`).val()) || 0;
      let advance = parseFloat($(`#${context}-advance`).val()) || 0;
      let balance = total - discount - advance;
      $(`#${context}-balance`).text(balance.toFixed(2));
    }

    $('.calc-input').on('input', function() { calculateBalance('add'); });
    $('.calc-input-edit').on('input', function() { calculateBalance('edit'); });

    // Save Payment
    $('#btn-save-payment').click(function() {
      let formData = $('#form-add-payment').serialize();
      $.post("{{ route('payments.store') }}", formData, function(response) {
        location.reload();
      }).fail(function(xhr) {
        alert("Error: " + xhr.responseJSON.message);
      });
    });

    // Edit Payment - Load Data
    $(document).on('click', '.btn-edit', function() {
      let id = $(this).data('id');
      $.get("/payments/" + id, function(data) {
        $('#edit-id').val(data.id);
        $('#edit-patient-id').val(data.patient_id);
        $('#edit-bill-date').val(data.bill_date);
        $('#edit-status').val(data.payment_status);
        $('#edit-total').val(data.total_amount);
        $('#edit-discount').val(data.discount);
        $('#edit-advance').val(data.advance_paid);
        $('#edit-method').val(data.payment_method);
        $('#edit-remarks').val(data.remarks);
        calculateBalance('edit');
      });
    });

    // Update Payment
    $('#btn-update-payment').click(function() {
      let id = $('#edit-id').val();
      let formData = $('#form-edit-payment').serialize();
      $.ajax({
        url: "/payments/" + id,
        type: 'PUT',
        data: formData,
        success: function(response) {
          location.reload();
        },
        error: function(xhr) {
          alert("Error updating payment.");
        }
      });
    });

    // Delete Payment
    $(document).on('click', '.btn-delete', function() {
      $('#delete-id').val($(this).data('id'));
    });

    $('#btn-confirm-delete').click(function() {
      let id = $('#delete-id').val();
      $.ajax({
        url: "/payments/" + id,
        type: 'DELETE',
        success: function(response) {
          location.reload();
        }
      });
    });
  });
</script>
@endpush
@endsection


