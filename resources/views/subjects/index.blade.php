@extends('adminlte::page')

@section('title', __('Subject Management'))

@section('content_header')
    <h1>{{ __('Subject Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('List of Subjects') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="newSubjectBtn">
                        <i class="fas fa-plus"></i> {{ __('Add Subject') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-control select2" id="filter_school_id">
                            <option value="">{{ __('All Schools') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control select2" id="filter_grade_id">
                            <option value="">{{ __('All Classes') }}</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Class') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="subjectList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Subject Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="subjectForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Subject') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="subject_id">
                    <div class="form-group">
                        <label>{{ __('School') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Choose School...') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Class') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="grade_id" id="grade_id" required style="width: 100%;">
                            <option value="">{{ __('Choose Class...') }}</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Subject Code') }}</label>
                        <input type="text" class="form-control" name="code" id="code" placeholder="{{ __('Auto-generated if empty') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Subject Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="{{ __('e.g. Mathematics') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Subject') }}</button>
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

        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        function loadSubjects() {
            let schoolId = $('#filter_school_id').val();
            let gradeId = $('#filter_grade_id').val();
            $.get("{{ route('subjects.index') }}", { school_id: schoolId, grade_id: gradeId }, function (data) {
                let rows = '';
                if (data.length === 0) {
                    rows = '<tr><td colspan="5" class="text-center text-muted">{{ __('No Found Records') }}</td></tr>';
                } else {
                    data.forEach(s => {
                        let schoolName = s.school ? s.school.name : '-';
                        let gradeName = s.grade ? s.grade.name : '-';
                        rows += `
                            <tr id="subject_${s.id}">
                                <td><span class="badge badge-light border">${schoolName}</span></td>
                                <td>${gradeName}</td>
                                <td>${s.code}</td>
                                <td>${s.name}</td>
                                <td>
                                    <button class="btn btn-xs btn-warning editSubject" data-id="${s.id}" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-xs btn-danger deleteSubject" data-id="${s.id}" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                }
                $('#subjectList').html(rows);
            });
        }

        loadSubjects();

        $('#filter_school_id, #filter_grade_id').change(function() {
            loadSubjects();
        });

        $('#newSubjectBtn').click(function() {
            $('#subjectForm').trigger("reset");
            $('#school_id').val('').trigger('change');
            $('#grade_id').val('').trigger('change');
            $('#subject_id').val('');
            $('#modalTitle').text("{{ __('Add Subject') }}");
            $('#subjectModal').modal('show');
        });

        $('#subjectForm').submit(function(e) {
            e.preventDefault();
            let id = $('#subject_id').val();
            let url = id ? `/admin/subjects/${id}` : "{{ route('subjects.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#subjectModal').modal('hide');
                    loadSubjects();
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
                        Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                    }
                }
            });
        });

        $('body').on('click', '.editSubject', function() {
            let id = $(this).data('id');
            $.get(`/admin/subjects/${id}`, function(data) {
                $('#subject_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#grade_id').val(data.grade_id).trigger('change');
                $('#code').val(data.code);
                $('#name').val(data.name);
                $('#modalTitle').text("{{ __('Edit Subject') }}");
                $('#subjectModal').modal('show');
            });
        });

        $('body').on('click', '.deleteSubject', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this subject?') }}")) {
                $.ajax({
                    url: `/admin/subjects/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#subject_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    },
                    error: function(xhr) {
                         Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                    }
                });
            }
        });
    });
</script>
@stop

<style>
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
</style>
