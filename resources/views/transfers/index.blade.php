@extends('adminlte::page')

@section('title', __('Student Transfers & LC'))

@section('content_header')
    <h1>{{ __('Student Transfers & Certificates') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">{{ __('Transfer Records') }}</h3>
                <div class="card-tools d-flex align-items-center">
                    <div class="mr-2" style="width: 200px;">
                        <select class="form-control select2" id="filter_school_id">
                            <option value="">{{ __('All Schools') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-warning btn-sm" id="newTransferBtn">
                        <i class="fas fa-file-export"></i> {{ __('Add Transfer Record') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Student') }}</th>
                            <th>{{ __('Transfer Date') }}</th>
                            <th>{{ __('To School') }}</th>
                            <th>{{ __('LC Number') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="transferList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="transferForm">
                <input type="hidden" name="id" id="transfer_id">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Record Student Transfer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('School') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Select School') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Student') }}</label>
                        <select class="form-control" name="student_id" required>
                            @foreach(\App\Models\Student::all() as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ __('Roll') }}: {{ $student->roll_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Transfer Date') }}</label>
                        <input type="date" class="form-control" name="transfer_date" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('To School / Reason') }}</label>
                        <textarea class="form-control" name="reason" rows="2" placeholder="{{ __('Reason for transfer...') }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Transfer To School') }}</label>
                        <input type="text" class="form-control" name="to_school" placeholder="{{ __('Target School Name') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Leaving Certificate (LC) Number') }}</label>
                        <input type="text" class="form-control" name="lc_number" placeholder="{{ __('e.g. LC-2024-001') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-warning">{{ __('Save Record') }}</button>
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

        function loadTransfers() {
            let schoolId = $('#filter_school_id').val();
            $.get("{{ route('transfers.index') }}", { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(t => {
                    let schoolName = t.school ? `<span class="badge badge-light border mr-1">${t.school.name}</span>` : '';
                    rows += `
                        <tr id="transfer_${t.id}">
                            <td>${schoolName}</td>
                            <td>${t.student ? t.student.name : "{{ __('Unknown') }}"}</td>
                            <td>${t.transfer_date}</td>
                            <td>${t.to_school || "{{ __('N/A') }}"}</td>
                            <td>${t.lc_number || "{{ __('N/A') }}"}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editTransfer" data-id="${t.id}" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteTransfer" data-id="${t.id}" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#transferList').html(rows);
            });
        }

        loadTransfers();

        $('#filter_school_id').change(function() {
            loadTransfers();
        });

        $('#newTransferBtn').click(function() {
            $('#transferForm').trigger("reset");
            $('#transfer_id').val('');
            $('#school_id').val('').trigger('change');
            $('.modal-title').text("{{ __('Record Student Transfer') }}");
            $('#transferModal').modal('show');
        });

        $('#transferForm').submit(function(e) {
            e.preventDefault();
            let id = $('#transfer_id').val();
            let url = id ? `/admin/transfers/${id}` : "{{ route('transfers.store') }}";
            let type = id ? "PUT" : "POST";
            
            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#transferModal').modal('hide');
                    loadTransfers();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                }
            });
        });

        $('body').on('click', '.editTransfer', function() {
            let id = $(this).data('id');
            $.get(`/admin/transfers/${id}`, function(data) {
                $('#transfer_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('select[name="student_id"]').val(data.student_id);
                $('input[name="transfer_date"]').val(data.transfer_date);
                $('textarea[name="reason"]').val(data.reason);
                $('input[name="to_school"]').val(data.to_school);
                $('input[name="lc_number"]').val(data.lc_number);
                $('.modal-title').text("{{ __('Edit Transfer Record') }}");
                $('#transferModal').modal('show');
            });
        });

        $('body').on('click', '.deleteTransfer', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this transfer record?') }}")) {
                $.ajax({
                    url: `/admin/transfers/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#transfer_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>

<style>
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
</style>
@stop
