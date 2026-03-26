@extends('adminlte::page')

@section('title', __('Fee Structure'))

@section('content_header')
    <h1>{{ __('Fee Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">{{ __('Fee Structures') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-warning btn-sm" id="newFeeBtn">
                        <i class="fas fa-plus"></i> {{ __('Add New Fee Type') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="feeList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Fee Modal -->
<div class="modal fade" id="feeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="feeForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Fee Structure') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="fee_id">
                    <div class="form-group">
                        <label>{{ __('Fee Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="{{ __('e.g. Tuition Fee') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Amount') }}</label>
                        <input type="number" class="form-control" name="amount" id="amount" required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-warning">{{ __('Save Fee Structure') }}</button>
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

        function loadFees() {
            $.get("{{ route('fee-structure.index') }}", function (data) {
                let rows = '';
                data.forEach(f => {
                    rows += `
                        <tr id="fee_${f.id}">
                            <td>${f.name}</td>
                            <td>${f.amount}</td>
                            <td>${f.description || ''}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editFee" data-id="${f.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteFee" data-id="${f.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#feeList').html(rows);
            });
        }

        loadFees();

        $('#newFeeBtn').click(function() {
            $('#feeForm').trigger("reset");
            $('#fee_id').val('');
            $('#modalTitle').text("{{ __('Add Fee Structure') }}");
            $('#feeModal').modal('show');
        });

        $('#feeForm').submit(function(e) {
            e.preventDefault();
            let id = $('#fee_id').val();
            let url = id ? `/admin/fee-structure/${id}` : "{{ route('fee-structure.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#feeModal').modal('hide');
                    loadFees();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + '<br>';
                        });
                        Swal.fire("{{ __('Validation Error') }}", errorMsg, 'error');
                    } else {
                        let msg = "{{ __('Something went wrong') }}";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire("{{ __('Error') }}", msg, 'error');
                    }
                }
            });
        });

        $('body').on('click', '.editFee', function() {
            let id = $(this).data('id');
            $.get(`/admin/fee-structure/${id}`, function(data) {
                $('#fee_id').val(data.id);
                $('#name').val(data.name);
                $('#amount').val(data.amount);
                $('#description').val(data.description);
                $('#modalTitle').text("{{ __('Edit Fee Structure') }}");
                $('#feeModal').modal('show');
            });
        });

        $('body').on('click', '.deleteFee', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this fee structure?') }}")) {
                $.ajax({
                    url: `/admin/fee-structure/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#fee_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
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
