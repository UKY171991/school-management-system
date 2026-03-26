@extends('adminlte::page')

@section('title', __('Classes Management'))

@section('content_header')
    <h1>{{ __('Classes Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('All Classes') }}</h3>
                <div class="card-tools d-flex align-items-center">
                    @if(auth()->user()->isMasterAdmin())
                    <div class="mr-2" style="width: 250px;">
                        <select class="form-control select2" id="filter_school_id">
                            <option value="">{{ __('All Admins') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <button type="button" class="btn btn-primary btn-sm" id="newGradeBtn">
                        <i class="fas fa-plus"></i> {{ __('Add Class') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="gradesTable">
                    <thead>
                        <tr>
                            <th>{{ __('School Name') }}</th>
                            <th>{{ __('Class Name') }}</th>
                            <th>{{ __('Class Teacher') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="gradeList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="gradeForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Class') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="grade_id">
                    @if(auth()->user()->isMasterAdmin())
                    <div class="form-group">
                        <label>{{ __('Select Admin Name') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Select Admin') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="school_id" id="school_id" value="{{ auth()->user()->school_id }}">
                    @endif
                    <div class="form-group">
                        <label>{{ __('Class Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="{{ __('e.g. Class 1') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Assign Class Teacher') }}</label>
                        <select class="form-control select2" name="teacher_id" id="teacher_id" style="width: 100%;">
                            <option value="">{{ __('Select Teacher') }}</option>
                        </select>
                        <small class="text-muted">{{ __('Only teachers from the selected school will be available.') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Class') }}</button>
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

        function loadGrades() {
            let schoolId = $('#filter_school_id').val();
            $.get("{{ route('grades.index') }}", { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(g => {
                    let schoolName = g.school ? `<span class="badge badge-light border mr-1">${g.school.name}</span>` : '';
                    let sectionsHtml = '';
                    if(g.sections && g.sections.length > 0) {
                        g.sections.forEach(sec => {
                            sectionsHtml += `<span class="badge badge-info mr-1">${sec.name}</span>`;
                        });
                    } else {
                        sectionsHtml = '<span class="text-muted small">{{ __('No sections') }}</span>';
                    }

                    rows += `
                        <tr id="grade_${g.id}">
                            <td>${schoolName}</td>
                            <td>
                                <div class="font-weight-bold">${g.name}</div>
                                <div class="mt-1">${sectionsHtml}</div>
                            </td>
                            <td>
                                ${g.teacher ? `<div class="d-flex align-items-center">
                                    <i class="fas fa-chalkboard-teacher mr-2 text-info"></i>
                                    <div>
                                        <strong>${g.teacher.name}</strong><br>
                                        <small class="text-muted">${g.teacher.email || ''}</small>
                                    </div>
                                </div>` : '<span class="text-muted small">{{ __('Not Assigned') }}</span>'}
                            </td>
                            <td>
                                <button class="btn btn-xs btn-warning editGrade" data-id="${g.id}" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteGrade" data-id="${g.id}" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                if ($.fn.DataTable.isDataTable('#gradesTable')) {
                    $('#gradesTable').DataTable().destroy();
                }
                $('#gradeList').html(rows);
                $('#gradesTable').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "order": [[ 0, "asc" ]]
                });
            });
        }

        loadGrades();

        $('#filter_school_id').change(function() {
            loadGrades();
        });

        $('#school_id').change(function() {
            let schoolId = $(this).val();
            let $teacherSelect = $('#teacher_id');
            
            $teacherSelect.html('<option value="">{{ __('Select Teacher') }}</option>');

            if(!schoolId) {
                return;
            }

            $.get("{{ route('teacher-profiles.index') }}", { school_id: schoolId }, function(data) {
                let options = '<option value="">{{ __('Select Teacher') }}</option>';
                data.forEach(teacher => {
                    options += `<option value="${teacher.id}">${teacher.name}</option>`;
                });
                $teacherSelect.html(options);
            });
        });

        $('#newGradeBtn').click(function() {
            $('#gradeForm').trigger("reset");
            $('#grade_id').val('');
            @if(auth()->user()->isMasterAdmin())
                $('#school_id').val('').trigger('change');
            @else
                // For non-master admin, automatically load teachers for their school
                let schoolId = $('#school_id').val();
                if(schoolId) {
                    $.get("{{ route('teacher-profiles.index') }}", { school_id: schoolId }, function(data) {
                        let options = '<option value="">{{ __('Select Teacher') }}</option>';
                        data.forEach(teacher => {
                            options += `<option value="${teacher.id}">${teacher.name}</option>`;
                        });
                        $('#teacher_id').html(options);
                    });
                }
            @endif
            $('#teacher_id').val('').trigger('change');
            $('.modal-title').text("{{ __('Add Class') }}");
            $('#gradeModal').modal('show');
        });

        $('#gradeForm').submit(function(e) {
            e.preventDefault();
            let id = $('#grade_id').val();
            let url = id ? `/admin/grades/${id}` : "{{ route('grades.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#gradeModal').modal('hide');
                    loadGrades();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            if(key === 'duplicate') {
                                errorMsg = value[0];
                            } else {
                                errorMsg += value[0] + '<br>';
                            }
                        });
                        Swal.fire("{{ __('Error') }}", errorMsg, 'error');
                    } else {
                        Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                    }
                }
            });
        });

        $('body').on('click', '.editGrade', function() {
            let id = $(this).data('id');
            $.get(`/admin/grades/${id}`, function(data) {
                $('#grade_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                
                // Load teachers for the school and then set the value
                if(data.school_id) {
                     $.get("{{ route('teacher-profiles.index') }}", { school_id: data.school_id }, function(teachers) {
                            let options = '<option value="">{{ __('Select Teacher') }}</option>';
                            teachers.forEach(teacher => {
                                let selected = (teacher.id == data.teacher_id) ? 'selected' : '';
                                options += `<option value="${teacher.id}" ${selected}>${teacher.name}</option>`;
                            });
                            $('#teacher_id').html(options);
                     });
                }
                
                $('#name').val(data.name);
                $('.modal-title').text("{{ __('Edit Class') }}");
                $('#gradeModal').modal('show');
            });
        });

        $('body').on('click', '.deleteGrade', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this class?') }}")) {
                $.ajax({
                    url: `/admin/grades/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#grade_${id}`).remove();
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
