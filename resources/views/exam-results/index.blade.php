@extends('adminlte::page')

@section('title', __('Exam Results'))

@section('content_header')
    <h1>{{ __('Exam Results') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header border-0 bg-light">
                <h3 class="card-title font-weight-bold">{{ __('Exam Results Management') }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted text-uppercase mb-1">{{ __('Filter by School') }}</label>
                            <select id="filter_school" class="form-control select2">
                                <option value="">{{ __('All Schools') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted text-uppercase mb-1">{{ __('Classes') }}</label>
                            <select id="filter_grade" class="form-control select2">
                                <option value="">{{ __('All Classes') }}</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted text-uppercase mb-1">{{ __('Exam Type') }} <span class="text-danger">*</span></label>
                            <select id="filter_exam" class="form-control select2">
                                <option value="">{{ __('Select Exam Type to View Results...') }}</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" data-school="{{ $exam->school_id }}">{{ $exam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-primary shadow-sm btn-block" id="resetFilter">
                            <i class="fas fa-undo mr-1"></i> {{ __('Reset') }}
                        </button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4" id="statisticsCards" style="display: none;">
                    <div class="col-md-3">
                        <div class="card bg-info">
                            <div class="card-body">
                                <h5 class="card-title text-white">Total Students</h5>
                                <h3 class="text-white" id="totalStudents">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success">
                            <div class="card-body">
                                <h5 class="card-title text-white">Passed</h5>
                                <h3 class="text-white" id="passedStudents">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger">
                            <div class="card-body">
                                <h5 class="card-title text-white">Failed</h5>
                                <h3 class="text-white" id="failedStudents">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning">
                            <div class="card-body">
                                <h5 class="card-title text-white">Average %</h5>
                                <h3 class="text-white" id="averagePercentage">0%</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped" id="resultsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Roll #') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Class') }}</th>
                            <th class="text-center">{{ __('Total Marks') }}</th>
                            <th class="text-center">{{ __('Max Marks') }}</th>
                            <th class="text-center">{{ __('Percentage') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th width="120" class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Student Detailed Results') }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-6">
                        <p class="mb-1"><b>{{ __('Student Name') }}:</b> <span id="detailStudentName"></span></p>
                        <p class="mb-1"><b>{{ __('Roll Number') }}:</b> <span id="detailRollNumber"></span></p>
                        <p class="mb-1"><b>{{ __('School') }}:</b> <span id="detailSchool"></span></p>
                    </div>
                    <div class="col-6 text-right">
                        <p class="mb-1"><b>{{ __('Class/Grade') }}:</b> <span id="detailGrade"></span></p>
                        <p class="mb-1"><b>{{ __('Exam') }}:</b> <span id="detailExam"></span></p>
                        <p class="mb-1"><b>{{ __('Percentage') }}:</b> <span id="detailPercentage"></span>%</p>
                    </div>
                </div>
                
                <div id="modalTableContainer">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('Subject') }}</th>
                                <th class="text-center">{{ __('Marks Obtained') }}</th>
                                <th class="text-center">{{ __('Maximum Marks') }}</th>
                                <th>{{ __('Remarks') }}</th>
                            </tr>
                        </thead>
                        <tbody id="detailMarksList"></tbody>
                        <tfoot class="font-weight-bold">
                            <tr class="bg-light">
                                <td class="text-uppercase">{{ __('Total') }}</td>
                                <td class="text-center" id="detailTotalMarks"></td>
                                <td class="text-center" id="detailMaxMarks"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Initialize Select2 with Bootstrap 4 theme
        $('.select2').select2({ theme: 'bootstrap4' });

        let table = $('#resultsTable').DataTable({
            processing: true,
            searching: false,
            ordering: false,
            ajax: {
                url: "{{ route('exam-results.index') }}",
                data: function(d) {
                    d.school_id = $('#filter_school').val();
                    d.grade_id = $('#filter_grade').val();
                    d.exam_id = $('#filter_exam').val();
                },
                dataSrc: ""
            },
            columns: [
                { 
                    data: 'position',
                    className: 'text-center font-weight-bold',
                    render: function(data) {
                        if (data === 1) return '<span class="badge badge-warning">🥇 ' + data + '</span>';
                        if (data === 2) return '<span class="badge badge-secondary">🥈 ' + data + '</span>';
                        if (data === 3) return '<span class="badge badge-danger">🥉 ' + data + '</span>';
                        return '<span class="badge badge-light">' + data + '</span>';
                    }
                },
                { 
                    data: 'roll_number',
                    render: function(data) {
                        return `<span class="badge badge-pill badge-light border px-3">${data || '-'}</span>`;
                    }
                },
                { 
                    data: 'name',
                    className: 'font-weight-bold'
                },
                { 
                    data: 'school',
                    render: function(data) {
                        return data ? `<span class="badge badge-light border text-muted">${data.name}</span>` : '-';
                    }
                },
                { 
                    data: 'grade',
                    render: function(data) {
                        return data ? `<span class="text-primary font-weight-bold">${data.name}</span>` : '<span class="text-muted">N/A</span>';
                    }
                },
                { 
                    data: 'total_marks',
                    className: 'text-center font-weight-bold'
                },
                { 
                    data: 'max_marks',
                    className: 'text-center'
                },
                { 
                    data: 'percentage',
                    className: 'text-center',
                    render: function(data) {
                        return `<span class="badge badge-info">${data}%</span>`;
                    }
                },
                { 
                    data: 'status',
                    className: 'text-center',
                    render: function(data) {
                        let translatedStatus = data === 'PASSED' ? '{{ __("PASSED") }}' : '{{ __("FAILED") }}';
                        let badgeClass = data === 'PASSED' ? 'badge-success' : 'badge-danger';
                        return `<span class="badge ${badgeClass}">${translatedStatus}</span>`;
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    render: function(data) {
                        return `<button class="btn btn-outline-primary btn-xs viewDetails" data-id="${data}"><i class="fas fa-eye mr-1"></i> {{ __("View") }}</button>`;
                    },
                    orderable: false
                }
            ]
        });

        function reloadTable() {
            table.ajax.reload();
            loadStatistics();
        }

        function loadStatistics() {
            let examId = $('#filter_exam').val();
            if (!examId) {
                $('#statisticsCards').hide();
                return;
            }

            $.get('/admin/exam-results/statistics', {
                exam_id: examId,
                school_id: $('#filter_school').val(),
                grade_id: $('#filter_grade').val()
            }, function(data) {
                $('#totalStudents').text(data.total_students);
                $('#passedStudents').text(data.passed_students);
                $('#failedStudents').text(data.failed_students);
                $('#averagePercentage').text(data.average_percentage + '%');
                $('#statisticsCards').show();
            });
        }

        $('#filter_school').on('change', function() {
            let schoolId = $(this).val();
            let examSelect = $('#filter_exam');
            let gradeSelect = $('#filter_grade');
            
            // Clear selections and disable with "Loading..." state
            examSelect.val(null).html('<option value="">{{ __("Loading Exams...") }}</option>').prop('disabled', true).trigger('change');
            gradeSelect.val(null).html('<option value="">{{ __("Loading Classes...") }}</option>').prop('disabled', true).trigger('change');

            let examsLoaded = false;
            let gradesLoaded = false;

            function checkAllLoaded() {
                if (examsLoaded && gradesLoaded) {
                    reloadTable();
                }
            }
            
            // Load exams based on selected school
            $.get("{{ route('exam-results.get-exams-by-school') }}", { school_id: schoolId }, function(exams) {
                let options = '<option value="">{{ __("Select Exam Type to View Results...") }}</option>';
                exams.forEach(function(exam) {
                    options += '<option value="' + exam.id + '">' + exam.name + '</option>';
                });
                examSelect.html(options);
            }).fail(function() {
                examSelect.html('<option value="">{{ __("Select Exam Type to View Results...") }}</option>');
            }).always(function() {
                examSelect.prop('disabled', false).trigger('change');
                examsLoaded = true;
                checkAllLoaded();
            });

            // Load grades based on selected school
            $.get("{{ route('exam-results.get-grades-by-school') }}", { school_id: schoolId }, function(grades) {
                let options = '<option value="">{{ __("All Classes") }}</option>';
                grades.forEach(function(grade) {
                    options += '<option value="' + grade.id + '">' + grade.name + '</option>';
                });
                gradeSelect.html(options);
            }).fail(function() {
                gradeSelect.html('<option value="">{{ __("All Classes") }}</option>');
            }).always(function() {
                gradeSelect.prop('disabled', false).trigger('change');
                gradesLoaded = true;
                checkAllLoaded();
            });
        });

        $('#filter_grade').on('change', reloadTable);
        $('#filter_exam').on('change', reloadTable);

        $('#resetFilter').click(function() {
            $('#filter_school').val('').trigger('change');
            $('#filter_grade').val('').trigger('change');
            $('#filter_exam').val('').trigger('change');
        });

        $('body').on('click', '.viewDetails', function() {
            let id = $(this).data('id');
            
            Swal.showLoading();
            
            $.get(`/admin/exam-results/student/${id}`, function(data) {
                Swal.close();
                $('#detailStudentName').text(data.student.name);
                $('#detailRollNumber').text(data.student.roll_number);
                $('#detailSchool').text(data.student.school ? data.student.school.name : '{{ __("N/A") }}');
                $('#detailGrade').text(data.student.grade ? data.student.grade.name : '{{ __("N/A") }}');
                
                // Update modal header for many exams
                $('.modal-title').text('{{ __("Student Exam Records") }}');
                
                let examsRows = '';
                if(data.results.length === 0) {
                    examsRows = '<tr><td colspan="5" class="text-center text-muted py-3">No exam records found for this student.</td></tr>';
                } else {
                    data.results.forEach(res => {
                        examsRows += `
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-primary">${res.exam_name}</div>
                                </td>
                                <td class="text-center font-weight-bold">${res.total_marks} / ${res.max_marks}</td>
                                <td class="text-center">
                                    <span class="badge badge-info">${res.percentage}%</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge ${res.status === 'PASSED' ? 'badge-success' : 'badge-danger'}">${res.status}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="/admin/exam-results/print/${id}?exam_id=${res.exam_id}" target="_blank" class="btn btn-xs btn-outline-info">
                                            <i class="fas fa-file-invoice mr-1"></i> Preview
                                        </a>
                                        <button onclick="window.open('/admin/exam-results/print/${id}?exam_id=${res.exam_id}', '_blank').print()" class="btn btn-xs btn-outline-primary ml-1">
                                            <i class="fas fa-print mr-1"></i> Print
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }
                
                // Replace the detailed marks table with an exams summary table
                let newTableHtml = `
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered shadow-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Exam Type</th>
                                    <th class="text-center">Total Marks</th>
                                    <th class="text-center">Percentage</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>${examsRows}</tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top text-center">
                        <h6 class="text-muted mb-3 font-weight-bold">CONSOLIDATED ACADEMIC RECORDS</h6>
                        <div class="row justify-content-center">
                            <div class="col-md-5">
                                <a href="/admin/exam-results/print-full/${id}" target="_blank" class="btn btn-outline-info btn-block shadow-sm">
                                    <i class="fas fa-file-pdf mr-2"></i> Full Result Preview
                                </a>
                            </div>
                            <div class="col-md-5">
                                <button onclick="window.open('/admin/exam-results/print-full/${id}', '_blank').print()" class="btn btn-primary btn-block shadow-sm">
                                    <i class="fas fa-print mr-2"></i> Print Full Result
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Target the area where table was
                $('#modalTableContainer').html(newTableHtml);
                
                // Hide specific detail fields as it's a list now
                $('#detailExam').closest('p').hide();
                $('#detailPercentage').closest('p').hide();

                $('#studentModal').modal('show');
            }).fail(function() {
                Swal.fire('{{ __("Error") }}', '{{ __("Failed to load student exam records.") }}', 'error');
            });
        });
    });
</script>
@stop
