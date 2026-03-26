@extends('adminlte::page')

@section('title', 'Teacher Timetable')

@section('css')
<style>
    @media print {
        .main-sidebar, .main-header, .card-header, .row.mb-3, .modal, .btn, .footer, .content-header, 
        .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
            display: none !important;
        }
        /* Except the print only header which we want to show */
        #printHeader {
            display: block !important;
            margin-bottom: 20px !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        table {
            width: 100% !important;
        }
        #printHeader {
            display: block !important;
            margin-bottom: 20px !important;
        }
        /* Make badges look like plain text in print */
        .badge {
            border: none !important;
            background: none !important;
            color: #000 !important;
            padding: 0 !important;
            font-size: inherit !important;
            font-weight: normal !important;
        }
        /* Hide ID, School, and Actions columns in print */
        #timetableTable th:nth-child(1), #timetableTable td:nth-child(1),
        #timetableTable th:nth-child(2), #timetableTable td:nth-child(2),
        #timetableTable th:nth-child(8), #timetableTable td:nth-child(8) {
            display: none !important;
        }
    }
</style>
@stop

@section('content_header')
    <h1>Teacher Timetable</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Print Only Header -->
        <div id="printHeader" class="d-none d-print-block mb-4">
            <h2 class="text-center font-weight-bold">Teacher Timetable</h2>
            <div class="row border-bottom pb-2">
                <div class="col-6">
                    <strong>School:</strong> <span id="print_school_name">-</span>
                </div>
                <div class="col-6 text-right">
                    <strong>Class:</strong> <span id="print_grade_name">-</span><br>
                    <strong>Section:</strong> <span id="print_section_name">-</span>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Timetable Entries</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-outline-secondary btn-sm mr-1" id="printTimetable">
                        <i class="fas fa-print"></i> Print Timetable
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" id="convertNewTimetable">
                        <i class="fas fa-plus"></i> Add Entry
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="filter_school_id" class="form-control select2">
                            <option value="">Filter by School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filter_teacher_id" class="form-control select2">
                            <option value="">Filter by Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" data-school="{{ $teacher->school_id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filter_grade_id" class="form-control select2">
                            <option value="">Filter by Class</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" data-school="{{ $grade->school_id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filter_section_id" class="form-control select2" disabled>
                            <option value="">Filter by Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" data-grade="{{ $section->grade_id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <table class="table table-bordered table-striped" id="timetableTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>School</th>
                            <th>Teacher</th>
                            <th>Class - Section</th>
                            <th>Subject</th>
                            <th>Time Slot</th>
                            <th>Day</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="timetableList">
                        <!-- Loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Timetable Modal -->
<div class="modal fade" id="timetableModal" tabindex="-1" role="dialog" aria-labelledby="timetableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form id="timetableForm">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="timetableModalLabel">
                        <i class="fas fa-clock mr-2"></i>Add Timetable Entry
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="timetable_id" id="timetable_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">School <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="school_id" required style="width: 100%;">
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Teacher <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="teacher_id" id="teacher_id" required style="width: 100%;">
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" data-school="{{ $teacher->school_id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Class / Grade</label>
                                <select class="form-control select2" id="grade_id" style="width: 100%;">
                                    <option value="">Select Class</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}" data-school="{{ $grade->school_id }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Section <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="section_id" id="section_id" required disabled style="width: 100%;">
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" data-grade="{{ $section->grade_id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="subject_id" id="subject_id" required style="width: 100%;">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" data-school="{{ $subject->school_id }}" data-grade="{{ $subject->grade_id }}">{{ $subject->name ?? 'Subject '.$subject->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Day <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="day" id="day" required style="width: 100%;">
                                    <option value="">Select Day</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Start Time</label>
                                <input type="time" class="form-control h-auto py-2" name="start_time" id="start_time" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">End Time</label>
                                <input type="time" class="form-control h-auto py-2" name="end_time" id="end_time" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 py-3">
                    <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="saveBtn">Save Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $('.select2').select2({ theme: 'bootstrap4' });
        $('#timetableModal .select2').select2({ theme: 'bootstrap4', dropdownParent: $('#timetableModal') });

        function loadTimetable() {
            let schoolId = $('#filter_school_id').val();
            let teacherId = $('#filter_teacher_id').val();
            let gradeId = $('#filter_grade_id').val();
            let sectionId = $('#filter_section_id').val();
            
            $.get("{{ route('teacher-timetable.index') }}", { 
                school_id: schoolId, 
                teacher_id: teacherId,
                grade_id: gradeId,
                section_id: sectionId
            }, function (data) {
                let rows = '';
                data.forEach(entry => {
                    let sectionName = entry.section ? (entry.section.name || 'Sec '+entry.section.id) : '-';
                    if(entry.section && entry.section.grade) { sectionName = `${entry.section.grade.name} - ${sectionName}`; }
                    let subjectName = entry.subject ? (entry.subject.name || 'Sub '+entry.subject.id) : '-';
                    let examDateStr = entry.exam_date ? `<span class="badge badge-light-primary"><i class="far fa-calendar-alt mr-1"></i> ${entry.exam_date}</span>` : '-';
                    
                    rows += `
                        <tr id="entry_${entry.id}">
                            <td>${entry.id}</td>
                            <td>${entry.teacher && entry.teacher.school ? `<span class="badge badge-light border">${entry.teacher.school.name}</span>` : '-'}</td>
                            <td><div class="font-weight-bold text-primary">${entry.teacher ? entry.teacher.name : '-'}</div></td>
                            <td>${sectionName}</td>
                            <td>${subjectName}</td>
                            <td><span class="badge badge-pill badge-light border"><i class="far fa-clock mr-1"></i> ${entry.start_time} - ${entry.end_time}</span></td>
                            <td><span class="badge badge-info">${entry.day}</span></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-outline-warning btn-sm border-0 editEntry" data-id="${entry.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm border-0 deleteEntry" data-id="${entry.id}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if ($.fn.DataTable.isDataTable('#timetableTable')) {
                    $('#timetableTable').DataTable().destroy();
                }
                $('#timetableList').html(rows);
                $('#timetableTable').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "pageLength": 10
                });
            });
        }

        $('#filter_school_id, #filter_teacher_id, #filter_grade_id, #filter_section_id').change(function() {
            loadTimetable();
        });
        
        // Store all options as original references for filtering
        const originalTeachers = $('#teacher_id option').clone();
        const originalFilterTeachers = $('#filter_teacher_id option').clone();
        const originalGrades = $('#grade_id option').clone();
        const originalSections = $('#section_id option').clone();
        const originalSubjects = $('#subject_id option').clone();

        $('#filter_school_id, #school_id').change(function() {
            let schoolId = $(this).val();
            let isModal = $(this).attr('id') == 'school_id';
            let teacherSelect = isModal ? $('#teacher_id') : $('#filter_teacher_id');
            let gradeSelect = isModal ? $('#grade_id') : null;
            
            // Filter Teachers
            let teachersToKeep = (isModal ? originalTeachers : originalFilterTeachers).filter(function() {
                let s = $(this).data('school');
                return !schoolId || !s || s == schoolId;
            });
            teacherSelect.html(teachersToKeep).val('').trigger('change.select2');

            // Filter Grades
            let gradesToKeep = (isModal ? originalGrades : originalGrades.clone()).filter(function() {
                let s = $(this).data('school');
                return !schoolId || !s || s == schoolId;
            });
            if (gradeSelect) {
                gradeSelect.html(gradesToKeep).val('').trigger('change.select2');
            } else {
                $('#filter_grade_id').html(gradesToKeep).val('').trigger('change.select2');
            }

            if(!isModal) loadTimetable();
        });

        $('#grade_id, #filter_grade_id').change(function() {
            let gradeId = $(this).val();
            let isModal = $(this).attr('id') == 'grade_id';
            let sectionSelect = isModal ? $('#section_id') : $('#filter_section_id');
            let sectionLabel = isModal ? sectionSelect.closest('.form-group').find('label') : null;
            
            let sectionsToKeep = originalSections.filter(function() {
                let g = $(this).data('grade');
                return !gradeId || !g || g == gradeId;
            });
            sectionSelect.html(sectionsToKeep).val('').trigger('change.select2');
            
            if(gradeId && sectionsToKeep.length > 1) {
                sectionSelect.prop('disabled', false);
                if (isModal) {
                    sectionSelect.prop('required', true);
                    sectionLabel.html('Section <span class="text-danger">*</span>');
                }
            } else {
                sectionSelect.prop('disabled', true);
                if (isModal) {
                    sectionSelect.prop('required', false);
                    sectionLabel.html('Section (Optional)');
                }
            }

            // Filter Subjects
            let subjectSelect = $('#subject_id');
            let subjectsToKeep = originalSubjects.filter(function() {
                let g = $(this).data('grade');
                return !gradeId || !g || g == gradeId;
            });
            subjectSelect.html(subjectsToKeep).val('').trigger('change.select2');
        });

        loadTimetable();

        $('#convertNewTimetable').click(function () {
            $('#timetableForm').trigger("reset");
            $('#timetableModalLabel').html("Add New Entry");
            $('#timetable_id').val('');
            $('#grade_id').val('').trigger('change');
            $('#timetableModal').modal('show');
        });

        $('#timetableForm').submit(function (e) {
            e.preventDefault();
            let id = $('#timetable_id').val();
            let url = id ? `/admin/teacher-timetable/${id}` : "{{ route('teacher-timetable.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                data: $(this).serialize(),
                url: url,
                type: type,
                dataType: 'json',
                success: function (data) {
                    $('#timetableForm').trigger("reset");
                    $('#timetableModal').modal('hide');
                    loadTimetable();
                    Swal.fire('Success', data.success, 'success');
                },
                error: function(xhr) {
                    let errorMsg = 'Something went wrong';
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMsg
                    });
                }
            });
        });


        $('body').on('click', '.editEntry', function () {
            let id = $(this).data('id');
            $.get(`/admin/teacher-timetable/${id}`, function (data) {
                $('#timetableModalLabel').html('<i class="fas fa-edit mr-2"></i>Edit Entry');
                $('#timetable_id').val(data.id);
                
                // 1. Set School trigger
                let schoolId = '';
                if (data.teacher && data.teacher.school_id) {
                    schoolId = data.teacher.school_id;
                } else if (data.section && data.section.grade && data.section.grade.school_id) {
                    schoolId = data.section.grade.school_id;
                }
                
                if (schoolId) {
                    $('#school_id').val(schoolId).trigger('change');
                }

                // Give small timeout for filters to process
                setTimeout(() => {
                    // 2. Set Teacher
                    if (data.teacher_id) {
                        $('#teacher_id').val(data.teacher_id).trigger('change');
                    }
                    
                    // 3. Set Grade & Section
                    if (data.section && data.section.grade_id) {
                        $('#grade_id').val(data.section.grade_id).trigger('change');
                        
                        // Small extra timeout for section list to load
                        setTimeout(() => {
                            $('#section_id').val(data.section_id).trigger('change');
                        }, 100);
                    }
                    
                    // 4. Set Subject
                    $('#subject_id').val(data.subject_id).trigger('change');
                    
                    // 5. Rest of the fields
                    $('#day').val(data.day).trigger('change');
                    $('#start_time').val(data.start_time);
                    $('#end_time').val(data.end_time);
                    
                    $('#timetableModal').modal('show');
                }, 200);
            });
        });

        $('body').on('click', '.deleteEntry', function () {
            let id = $(this).data('id');
            if (confirm("Are you sure?")) {
                $.ajax({
                    type: "DELETE",
                    url: `/admin/teacher-timetable/${id}`,
                    success: function (data) {
                        $(`#entry_${id}`).remove();
                        Swal.fire('Deleted', data.success, 'success');
                    }
                });
            }
        });
        
        $('body').on('click', '#printTimetable', function () {
            let school = $('#filter_school_id option:selected').text();
            let teacher = $('#filter_teacher_id option:selected').text();
            let grade = $('#filter_grade_id option:selected').text();
            let section = $('#filter_section_id option:selected').text();
            
            if ($('#filter_school_id').val() == '' && $('#filter_teacher_id').val() == '' && $('#filter_grade_id').val() == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Filter Required',
                    text: 'Please select at least one filter (School, Teacher or Class) before printing.'
                });
                return;
            }

            $('#print_school_name').text($('#filter_school_id').val() ? school : 'All Schools');
            $('#print_grade_name').text($('#filter_grade_id').val() ? grade : 'All Classes');
            $('#print_section_name').text($('#filter_section_id').val() ? section : 'All Sections');
            
            window.print();
        });
    });
</script>
@stop
