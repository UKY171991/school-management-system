@extends('adminlte::page')

@section('title', __('Student Attendance'))

@section('content_header')
    <h1>{{ __('Student Attendance') }}</h1>
@stop

@section('content')
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Mark Attendance') }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ __('School') }}</label>
                            <select id="filter_school_id" class="form-control select2" style="width: 100%;">
                                <option value="">{{ __('Select School') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ __('Class/Grade') }}</label>
                            <select id="filter_grade" class="form-control select2" style="width: 100%;">
                                <option value="">{{ __('Select Class') }}</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" data-school="{{ $grade->school_id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ __('Section') }}</label>
                            <select id="filter_section" class="form-control select2" style="width: 100%;">
                                <option value="">{{ __('All Sections') }}</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" data-grade="{{ $section->grade_id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ __('Date') }}</label>
                            <input type="date" id="attendance_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <form id="attendanceForm">
                    <input type="hidden" name="date" id="hidden_date">
                    <input type="hidden" name="school_id" id="hidden_school_id">
                    
                    <div class="d-flex justify-content-end mb-2 d-none" id="bulkActions">
                        <button type="button" class="btn btn-sm btn-outline-success mr-2" id="markAllPresent">
                            <i class="fas fa-check"></i> {{ __('Mark All Present') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="markAllAbsent">
                            <i class="fas fa-times"></i> {{ __('Mark All Absent') }}
                        </button>
                    </div>

                    <table class="table table-bordered table-striped d-none" id="attendanceTable">
                        <thead>
                            <tr>
                                <th>{{ __('Roll No') }}</th>
                                <th>{{ __('Student Name') }}</th>
                                <th>{{ __('School') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody id="studentList"></tbody>
                    </table>
                    <div class="mt-3 d-none" id="saveBtnContainer">
                        <button type="submit" class="btn btn-success float-right">{{ __('Save Attendance') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    var allGrades = @json($grades);
    var allSections = @json($sections);

    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        function fetchStudents() {
            let schoolId = $('#filter_school_id').val();
            let gradeId = $('#filter_grade').val();
            let sectionId = $('#filter_section').val();
            let date = $('#attendance_date').val();
            
            if(!schoolId || !gradeId) {
                // Silently return if required filters are not selected
                return;
            }

            $.get("/admin/attendance", { school_id: schoolId, grade_id: gradeId, section_id: sectionId, date: date }, function(students) {
                let rows = '';
                if(students.length === 0) {
                    rows = '<tr><td colspan="4" class="text-center text-muted py-4"><i class="fas fa-exclamation-circle mr-2"></i>{{ __('No records found') }}</td></tr>';
                    $('#saveBtnContainer, #bulkActions').addClass('d-none');
                } else {
                    students.forEach(s => {
                        let status = s.attendances.length > 0 ? s.attendances[0].status : 'present';
                        rows += `
                            <tr id="student_${s.id}">
                                <td>${s.roll_number}</td>
                                <td>${s.name}</td>
                                <td>${s.school ? s.school.name : "{{ __('N/A') }}"}</td>
                                <td>
                                     <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-success btn-xs ${status == 'present' ? 'active' : ''}">
                                            <input type="radio" name="attendance[${s.id}]" value="present" ${status == 'present' ? 'checked' : ''}> {{ __('Present') }}
                                        </label>
                                        <label class="btn btn-outline-danger btn-xs ${status == 'absent' ? 'active' : ''}">
                                            <input type="radio" name="attendance[${s.id}]" value="absent" ${status == 'absent' ? 'checked' : ''}> {{ __('Absent') }}
                                        </label>
                                        <label class="btn btn-outline-warning btn-xs ${status == 'late' ? 'active' : ''}">
                                            <input type="radio" name="attendance[${s.id}]" value="late" ${status == 'late' ? 'checked' : ''}> {{ __('Late') }}
                                        </label>
                                        <label class="btn btn-outline-info btn-xs ${status == 'excused' ? 'active' : ''}">
                                            <input type="radio" name="attendance[${s.id}]" value="excused" ${status == 'excused' ? 'checked' : ''}> {{ __('Excused') }}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    $('#saveBtnContainer').removeClass('d-none');
                }

                $('#studentList').html(rows);
                $('#hidden_date').val(date);
                $('#hidden_school_id').val(schoolId);
                $('#attendanceTable, #bulkActions').removeClass('d-none');
                $('#saveBtnContainer').removeClass('d-none');
            });
        }

        $('#filter_section, #attendance_date').change(function() {
            fetchStudents();
        });

        // Dependent Dropdowns (Data Rebuild Approach)
        $('#filter_school_id').change(function() {
            let schoolId = $(this).val();
            let gradeSelect = $('#filter_grade');
            let sectionSelect = $('#filter_section');
            
            // Clear and populate grades
            gradeSelect.empty().append('<option value="">{{ __('Select Class') }}</option>');
            allGrades.forEach(function(g) {
                if(!schoolId || g.school_id == schoolId) {
                    let option = new Option(g.name, g.id, false, false);
                    gradeSelect.append(option);
                }
            });
            gradeSelect.val('').trigger('change.select2'); // Reset grade and trigger its change handler

            // Also clear sections immediately as school changed
            sectionSelect.empty().append('<option value="">{{ __('All Sections') }}</option>');
            sectionSelect.val('').trigger('change.select2');
        });

        $('#filter_grade').change(function() {
            let gradeId = $(this).val();
            let sectionSelect = $('#filter_section');
            
            sectionSelect.empty().append('<option value="">{{ __('All Sections') }}</option>');
            
            if(gradeId) {
                allSections.forEach(function(s) {
                    if(s.grade_id == gradeId) {
                        let option = new Option(s.name, s.id, false, false);
                        sectionSelect.append(option);
                    }
                });
            }
            sectionSelect.val('').trigger('change.select2'); // Reset section

            // Trigger fetch
            fetchStudents();
        });

        // Bulk Actions
        $('#markAllPresent').click(function() {
            $('input[value="present"]').prop('checked', true).trigger('change');
            $('input[value="present"]').parent().addClass('active');
            $('input[value="present"]').parent().siblings().removeClass('active');
        });

        $('#markAllAbsent').click(function() {
            $('input[value="absent"]').prop('checked', true).trigger('change');
            $('input[value="absent"]').parent().addClass('active');
            $('input[value="absent"]').parent().siblings().removeClass('active');
        });

        $('#attendanceForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/admin/attendance",
                type: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                }
            });
        });
    });
</script>
@stop
