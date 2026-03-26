@extends('adminlte::page')

@section('title', __('Payroll'))

@section('content_header')
    <h1>{{ __('Payroll Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">{{ __('Salary Records') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" id="newSalaryBtn">
                        <i class="fas fa-plus"></i> {{ __('Generate Salary') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select id="filter_school_id" class="form-control select2">
                            <option value="">{{ __('Filter by School') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="filter_teacher_id" class="form-control select2">
                            <option value="">{{ __('Filter by Teacher') }}</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <table class="table table-bordered table-striped" id="payrollTable">
                    <thead>
                        <tr>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Teacher') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Month/Year') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="salaryList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Salary Modal -->
<div class="modal fade" id="salaryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="salaryForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Generate Salary') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="salary_id">
                    <div class="form-group row-school">
                        <label>{{ __('School') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Select School') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row-teacher">
                        <label>{{ __('Teacher') }}</label>
                        <select class="form-control" name="teacher_id" id="teacher_id" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Amount') }}</label>
                        <input type="number" class="form-control" name="amount" id="amount" required min="0">
                    </div>
                    <div class="row row-date">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Month') }}</label>
                                <select class="form-control" name="month" id="month" required>
                                    @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                                        <option value="{{ $m }}">{{ __($m) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Year') }}</label>
                                <input type="number" class="form-control" name="year" id="year" value="{{ date('Y') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="unpaid">{{ __('Unpaid') }}</option>
                            <option value="paid">{{ __('Paid') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('Save Record') }}</button>
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

        function loadSalaries() {
            let schoolId = $('#filter_school_id').val();
            let teacherId = $('#filter_teacher_id').val();
            $.get("{{ route('payroll.index') }}", { school_id: schoolId, teacher_id: teacherId }, function (data) {
                let rows = '';
                data.forEach(s => {
                    let schoolName = s.school ? `<span class="badge badge-light border mr-1">${s.school.name}</span>` : '';
                    let badgeClass = s.status == 'paid' ? 'badge-success' : 'badge-warning';
                    rows += `
                        <tr id="salary_${s.id}">
                            <td>${schoolName}</td>
                            <td>${s.teacher.name}</td>
                            <td>${s.amount}</td>
                            <td>${s.month} ${s.year}</td>
                            <td><span class="badge ${badgeClass}">${s.status == 'paid' ? '{{ __("Paid") }}' : '{{ __("Unpaid") }}'}</span></td>
                            <td>
                                <button class="btn btn-xs btn-warning editSalary" data-id="${s.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteSalary" data-id="${s.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                if ($.fn.DataTable.isDataTable('#payrollTable')) {
                    $('#payrollTable').DataTable().destroy();
                }
                $('#salaryList').html(rows);
                $('#payrollTable').DataTable({
                    "responsive": true, 
                    "autoWidth": false
                });
            });
        }

        $('#filter_teacher_id, #filter_school_id').change(function() {
            loadSalaries();
        });

        loadSalaries();

        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        $('#newSalaryBtn').click(function() {
            $('#salaryForm').trigger("reset");
            $('#salaryForm').trigger("reset");
            $('#salary_id').val('');
            $('#school_id').val('').trigger('change');
            $('#year').val(new Date().getFullYear()); // Ensure current year
            $('.row-school').show();
            $('.row-teacher').show();
            $('.row-date').show();
            $('#modalTitle').text("{{ __('Generate Salary') }}");
            $('#salaryModal').modal('show');
        });

        $('#salaryForm').submit(function(e) {
            e.preventDefault();
            let id = $('#salary_id').val();
            let url = id ? `/admin/payroll/${id}` : "{{ route('payroll.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#salaryModal').modal('hide');
                    loadSalaries();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + '<br>';
                        });
                        Swal.fire('Validation Error', errorMsg, 'error');
                    } else {
                        Swal.fire('Error', 'Something went wrong: ' + xhr.statusText, 'error');
                    }
                }
            });
        });

        $('body').on('click', '.editSalary', function() {
            let id = $(this).data('id');
            $.get(`/admin/payroll/${id}`, function(data) {
                $('#salary_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#teacher_id').val(data.teacher_id);
                $('#amount').val(data.amount);
                $('#month').val(data.month);
                $('#year').val(data.year);
                $('#status').val(data.status);
                $('.row-school').show();
                $('.row-teacher').show();
                $('.row-date').show();
                $('#modalTitle').text('Update Salary Status');
                $('#salaryModal').modal('show');
            });
        });

        $('body').on('click', '.deleteSalary', function() {
            let id = $(this).data('id');
            if(confirm("Remove this payroll record?")) {
                $.ajax({
                    url: `/admin/payroll/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#salary_${id}`).remove();
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
