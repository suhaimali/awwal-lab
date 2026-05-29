@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Payments & Billing</h4>
        </div>
        <div class="ms-auto">
          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-payment">
            <i class="fa fa-plus-circle me-5"></i> Add New Payment
          </button>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="box">
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped" id="payments-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Bill Date</th>
                      <th>Patient</th>
                      <th>Total</th>
                      <th>Discount</th>
                      <th>Advance</th>
                      <th>Balance</th>
                      <th>Status</th>
                      <th class="text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($payments as $p)
                      <tr>
                        <td>#{{ $p->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->bill_date)->format('d M Y') }}</td>
                        <td>{{ optional($p->patient)->first_name }} {{ optional($p->patient)->last_name }}</td>
                        <td>₹{{ number_format($p->total_amount ?? 0, 2) }}</td>
                        <td>₹{{ number_format($p->discount ?? 0, 2) }}</td>
                        <td>₹{{ number_format($p->advance_paid ?? 0, 2) }}</td>
                        <td>₹{{ number_format($p->balance_due ?? 0, 2) }}</td>
                        <td>
                          <span class="badge badge-{{ $p->payment_status == 'Paid' ? 'success' : ($p->payment_status == 'Partial' ? 'warning' : 'danger') }}">
                            {{ $p->payment_status }}
                          </span>
                        </td>
                        <td class="text-end">
                          <button class="btn btn-warning-light btn-sm btn-edit" data-id="{{ $p->id }}" data-bs-toggle="modal" data-bs-target="#modal-edit-payment">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button class="btn btn-danger-light btn-sm btn-delete" data-id="{{ $p->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete-payment">
                            <i class="fa fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Add Payment Modal -->
<div class="modal center-modal fade" id="modal-add-payment" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-add-payment">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Patient</label>
                <select class="form-select" name="patient_id" required>
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
                <label class="form-label">Bill Date</label>
                <input type="date" class="form-control" name="bill_date" value="{{ date('Y-m-d') }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Payment Status</label>
                <select class="form-select" name="payment_status" required>
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
                <label class="form-label">Total Amount (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input" name="total_amount" id="add-total" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Discount (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input" name="discount" id="add-discount" value="0.00">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Advance Paid (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input" name="advance_paid" id="add-advance" value="0.00">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select class="form-select" name="payment_method" required>
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
            <label class="form-label">Remarks</label>
            <textarea class="form-control" name="remarks" rows="2"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-save-payment">Save Payment</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal center-modal fade" id="modal-edit-payment" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-edit-payment">
          <input type="hidden" name="id" id="edit-id">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Patient</label>
                <select class="form-select" name="patient_id" id="edit-patient-id" required>
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
                <label class="form-label">Bill Date</label>
                <input type="date" class="form-control" name="bill_date" id="edit-bill-date" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Payment Status</label>
                <select class="form-select" name="payment_status" id="edit-status" required>
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
                <label class="form-label">Total Amount (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input-edit" name="total_amount" id="edit-total" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Discount (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input-edit" name="discount" id="edit-discount">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Advance Paid (₹)</label>
                <input type="number" step="0.01" class="form-control calc-input-edit" name="advance_paid" id="edit-advance">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select class="form-select" name="payment_method" id="edit-method" required>
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
            <label class="form-label">Remarks</label>
            <textarea class="form-control" name="remarks" id="edit-remarks" rows="2"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-update-payment">Update Payment</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal center-modal fade" id="modal-delete-payment" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="fa fa-warning fa-4x text-danger mb-15"></i>
        <h4>Confirm Deletion</h4>
        <p>Are you sure you want to delete this payment record? This action cannot be undone.</p>
        <input type="hidden" id="delete-id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="btn-confirm-delete">Delete Permanently</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
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
@endsection
