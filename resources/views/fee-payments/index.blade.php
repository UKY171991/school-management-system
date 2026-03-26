@extends('adminlte::page')

@section('title', __('Fee Payments'))

@section('content_header')
    <h1>{{ __('Fee Payment Transactions') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">{{ __('Recent Payments') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" id="newPaymentBtn">
                        <i class="fas fa-plus"></i> {{ __('Collect Fee') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Student') }}</th>
                            <th>{{ __('Fee Type') }}</th>
                            <th>{{ __('Amount Paid') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="paymentList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="paymentForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Collect Fee Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="payment_id">
                    <div class="form-group">
                        <label>{{ __('Student') }}</label>
                        <select class="form-control" name="student_id" id="student_id" required>
                            @foreach(\App\Models\Student::all() as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ __('Roll') }}: {{ $student->roll_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Fee Type (Structure)') }}</label>
                        <select class="form-control" name="fee_structure_id" id="fee_structure_id" required>
                            @foreach(\App\Models\FeeStructure::all() as $fs)
                                <option value="{{ $fs->id }}">{{ $fs->name }} ({{ $fs->amount }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Amount Paid') }}</label>
                        <input type="number" class="form-control" name="amount_paid" id="amount_paid" required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Payment Date') }}</label>
                        <input type="date" class="form-control" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="Paid">{{ __('Paid') }}</option>
                            <option value="Partial">{{ __('Partial') }}</option>
                            <option value="Pending">{{ __('Pending') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('Save Payment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        function loadPayments() {
            $.get("{{ route('fee-payments.index') }}", function (data) {
                let rows = '';
                data.forEach(p => {
                    let statusText = p.status == 'Paid' ? '{{ __("Paid") }}' : (p.status == 'Partial' ? '{{ __("Partial") }}' : '{{ __("Pending") }}');
                    rows += `
                        <tr id="payment_${p.id}">
                            <td>${studentName}</td>
                            <td>${feeName}</td>
                            <td>${p.amount_paid}</td>
                            <td>${p.payment_date}</td>
                            <td><span class="badge ${badgeClass}">${statusText}</span></td>
                            <td>
                                <button class="btn btn-xs btn-warning editPayment" data-id="${p.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deletePayment" data-id="${p.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#paymentList').html(rows);
            });
        }

        loadPayments();

        $('#newPaymentBtn').click(function() {
            $('#paymentForm').trigger("reset");
            $('#payment_id').val('');
            $('#modalTitle').text('{{ __("Collect Fee Payment") }}');
            $('#paymentModal').modal('show');
        });

        $('#paymentForm').submit(function(e) {
            e.preventDefault();
            let id = $('#payment_id').val();
            let url = id ? `/admin/fee-payments/${id}` : "{{ route('fee-payments.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#paymentModal').modal('hide');
                    loadPayments();
                    Swal.fire('{{ __("Success") }}', data.success, 'success');
                },
                error: function(xhr) {
                    let msg = 'Something went wrong';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                         msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    Swal.fire('{{ __("Error") }}', msg, 'error');
                }
            });
        });

        $('body').on('click', '.editPayment', function() {
            let id = $(this).data('id');
            $.get(`/admin/fee-payments/${id}`, function(data) {
                $('#payment_id').val(data.id);
                $('#student_id').val(data.student_id);
                $('#fee_structure_id').val(data.fee_structure_id);
                $('#amount_paid').val(data.amount_paid);
                $('#payment_date').val(data.payment_date);
                $('#status').val(data.status);
                $('#modalTitle').text('{{ __("Edit Payment Record") }}');
                $('#paymentModal').modal('show');
            }).fail(function() {
                Swal.fire('{{ __("Error") }}', '{{ __("Could not fetch payment data") }}', 'error');
            });
        });

        $('body').on('click', '.deletePayment', function() {
            let id = $(this).data('id');
                    if(confirm('{{ __("Delete this payment record?") }}')) {
                        $.ajax({
                            url: `/admin/fee-payments/${id}`,
                            type: "DELETE",
                            success: function(data) {
                                $(`#payment_${id}`).remove();
                                Swal.fire('{{ __("Deleted") }}', data.success, 'success');
                            },
                    error: function(xhr) {
                        let msg = 'Something went wrong';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });
</script>
@stop
