@extends('adminlte::page')

@section('title', __('Report Cards'))

@section('content_header')
    <h1>{{ __('Generate Report Cards') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-danger">
            <div class="card-header border-0 bg-light">
                <h3 class="card-title font-weight-bold">{{ __('Report Card Generation') }}</h3>
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
                            <label class="small font-weight-bold text-muted text-uppercase mb-1">{{ __('Filter by Class') }}</label>
                            <select id="filter_grade" class="form-control select2">
                                <option value="">{{ __('All Classes') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted text-uppercase mb-1">{{ __('Select Exam') }} <span class="text-danger">*</span></label>
                            <select id="filter_exam" class="form-control select2">
                                <option value="">{{ __('Select Exam to Generate...') }}</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" data-school="{{ $exam->school_id }}">{{ $exam->name }} {{ $exam->type ? '('.$exam->type.')' : '' }} [{{ $exam->session ?? 'N/A' }}]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger shadow-sm btn-block" id="resetFilter">
                            <i class="fas fa-undo mr-1"></i> {{ __('Reset') }}
                        </button>
                    </div>
                </div>

                <table class="table table-bordered table-striped" id="studentsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Roll #') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Class') }}</th>
                            <th width="150" class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Report Card Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Student Report Card') }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="printArea">
                <div class="report-container">
                    <div class="text-center mb-4">
                        <h2 class="d-none d-print-block"><b id="reportSchoolName"></b></h2>
                        <h3 class="text-uppercase" style="border-bottom: 2px solid #000; display: inline-block; padding-bottom: 5px;"><b>{{ __('Academic Report Card') }}</b></h3>
                        <h5 id="reportExamName" class="mt-2 text-muted"></h5>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-6">
                            <p class="mb-1"><b>{{ __('Student Name') }}:</b> <span id="reportStudentName"></span></p>
                            <p class="mb-1"><b>{{ __('Roll Number') }}:</b> <span id="reportRollNumber"></span></p>
                        </div>
                        <div class="col-6 text-right">
                            <p class="mb-1"><b>{{ __('Class/Grade') }}:</b> <span id="reportGrade"></span></p>
                            <p class="mb-1"><b>{{ __('Date') }}:</b> {{ date('d-m-Y') }}</p>
                        </div>
                    </div>
                    
                    <table class="table table-bordered mb-4">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('Subject') }}</th>
                                <th class="text-center">{{ __('Marks Obtained') }}</th>
                                <th class="text-center">{{ __('Maximum Marks') }}</th>
                                <th>{{ __('Remarks') }}</th>
                            </tr>
                        </thead>
                        <tbody id="reportMarksList"></tbody>
                        <tfoot class="font-weight-bold">
                            <tr class="bg-light">
                                <td class="text-uppercase">{{ __('Total') }}</td>
                                <td class="text-center" id="reportTotalMarks"></td>
                                <td class="text-center" id="reportMaxMarks"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="row mt-4 align-items-center">
                        <div class="col-6">
                            <h4 class="mb-0"><b>{{ __('Percentage') }}:</b> <span id="reportPercentage"></span>%</h4>
                        </div>
                        <div class="col-6 text-right">
                            <h4 class="mb-0"><b>{{ __('Result') }}:</b> <span id="reportResult" class="badge badge-lg"></span></h4>
                        </div>
                    </div>

                    <div class="row mt-5 d-none d-print-flex">
                        <div class="col-4 text-center">
                            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">{{ __('Class Teacher') }}</div>
                        </div>
                        <div class="col-4 text-center">
                            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">{{ __('Principal') }}</div>
                        </div>
                        <div class="col-4 text-center">
                            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">{{ __('Parent Signature') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> {{ __('Print') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Hide everything by default */
        body * {
            visibility: hidden;
        }
        
        /* Make the print area and its children visible */
        #printArea, #printArea * {
            visibility: visible;
        }

        /* Position the print area to fill the page, ignoring parent constraints */
        #printArea {
            position: fixed;
            left: 0;
            top: 0;
            width: 100vw !important;
            height: 100vh !important;
            margin: 0 !important;
            padding: 20px !important;
            background-color: white;
            z-index: 99999;
            overflow: hidden; /* Prevent double scrollbars in print */
        }
        
        /* Neutralize Bootstrap modal constraints */
        .modal, .modal-dialog, .modal-content, .modal-body {
            width: 100% !important;
            max-width: none !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            position: static !important;
            transform: none !important;
        }

        /* Custom Print Styling */
        .card-header, .card-footer, .btn, .no-print {
            display: none !important;
        }

        #printArea h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            color: #000;
        }

        #printArea h5 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }

        .report-container {
            border: 2px solid #000;
            padding: 30px;
            height: 98vh; /* Use almost full height */
            box-sizing: border-box;
        }

        .table-bordered {
            border: 1px solid #000 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
        }

        .badge {
            border: 1px solid #000;
            color: #000 !important;
            background: none !important;
            font-size: 16px;
            padding: 5px 10px;
        }
    }
</style>
@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Initialize Select2 with Bootstrap 4 theme
        $('.select2').select2({ theme: 'bootstrap4' });

        let table = $('#studentsTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('report-cards.index') }}",
                data: function(d) {
                    d.school_id = $('#filter_school').val();
                    d.grade_id = $('#filter_grade').val();
                },
                dataSrc: ""
            },
            columns: [
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
                    data: 'id',
                    className: 'text-center',
                    render: function(data) {
                        return `<button class="btn btn-outline-danger btn-xs viewReport" data-id="${data}"><i class="fas fa-file-invoice mr-1"></i> {{ __('View Report') }}</button>`;
                    },
                    orderable: false
                }
            ]
        });

        function reloadTable() {
            table.ajax.reload();
        }

        // Helper to show sequence error
        function showSequenceError(target) {
            Swal.fire({
                title: '{{ __("Selection Required") }}',
                text: '{{ __("Please select a ") }}' + target + ' {{ __("first.") }}',
                icon: 'warning',
                confirmButtonColor: '#d33'
            });
        }

        // Enforcement: Disable dependent fields initially
        $('#filter_grade, #filter_exam').prop('disabled', true);

        const rawGrades = @json($grades);

        function populateGrades(schoolId) {
            let gradeSelect = $('#filter_grade');
            gradeSelect.empty().append('<option value="">{{ __("All Classes") }}</option>');
            
            let filteredGrades = rawGrades;
            if (schoolId) {
                filteredGrades = rawGrades.filter(g => String(g.school_id) === String(schoolId));
            }
            
            if (!schoolId) {
                // Group by school name
                let schoolNames = [...new Set(filteredGrades.map(g => g.school ? g.school.name : 'Unknown School'))];
                schoolNames.sort().forEach(schoolName => {
                    let group = $('<optgroup>').attr('label', schoolName);
                    filteredGrades.filter(g => (g.school ? g.school.name : 'Unknown School') === schoolName).forEach(grade => {
                        group.append(new Option(grade.name, grade.id));
                    });
                    gradeSelect.append(group);
                });
            } else {
                filteredGrades.forEach(grade => {
                    gradeSelect.append(new Option(grade.name, grade.id));
                });
            }
            gradeSelect.trigger('change.select2');
        }

        $('#filter_school').on('change', function() {
            let schoolId = $(this).val();
            let gradeSelect = $('#filter_grade');
            let examSelect = $('#filter_exam');
            
            if(schoolId) {
                gradeSelect.prop('disabled', false);
                populateGrades(schoolId);

                // Filter Exams based on School
                examSelect.find('option').each(function() {
                    let examSchool = $(this).data('school');
                    if(examSchool && examSchool != schoolId) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            } else {
                gradeSelect.prop('disabled', true).val('').trigger('change');
                examSelect.prop('disabled', true).val('').trigger('change');
                populateGrades('');
            }
            
            gradeSelect.val('').trigger('change');
            reloadTable();
        });

        $('#filter_grade').on('change', function() {
            let gradeId = $(this).val();
            let schoolId = $('#filter_school').val();
            
            if(gradeId) {
                $('#filter_exam').prop('disabled', false);
            } else {
                if(!schoolId) $('#filter_exam').prop('disabled', true).val('').trigger('change');
            }
            reloadTable();
        });

        // Sequence Enforcement Alerts
        $('#filter_grade').on('select2:opening', function(e) {
            if (!$('#filter_school').val()) {
                e.preventDefault();
                showSequenceError('{{ __("School") }}');
            }
        });

        $('#filter_exam').on('select2:opening', function(e) {
            if (!$('#filter_school').val()) {
                e.preventDefault();
                showSequenceError('{{ __("School") }}');
            }
        });

        $('#resetFilter').click(function() {
            $('#filter_school').val('').trigger('change');
            $('#filter_grade').val('').trigger('change');
            $('#filter_exam').val('').trigger('change');
        });

        $('body').on('click', '.viewReport', function() {
            let id = $(this).data('id');
            let examId = $('#filter_exam').val();
            
            if(!examId) {
                Swal.fire('{{ __("Warning") }}', '{{ __("Please select an Exam from the filter above to generate the report.") }}', 'warning');
                return;
            }

            // Show loading state
            Swal.showLoading();
            
            $.get(`/admin/report-cards/${id}`, { exam_id: examId }, function(data) {
                Swal.close();
                $('#reportSchoolName').text(data.student.school ? data.student.school.name : 'SMART SCHOOL');
                let sessionText = data.exam.session ? ` (Session: ${data.exam.session})` : '';
                $('#reportExamName').text(data.exam.name + sessionText);
                $('#reportStudentName').text(data.student.name);
                $('#reportRollNumber').text(data.student.roll_number);
                $('#reportGrade').text(data.student.grade ? data.student.grade.name : 'N/A');
                
                let marksRows = '';
                let totalMaxPossible = 0;
                
                if(data.marks.length === 0) {
                     marksRows = '<tr><td colspan="4" class="text-center text-muted py-3">{{ __("No marks recorded for this student in the selected exam.") }}</td></tr>';
                } else {
                    data.marks.forEach(m => {
                        let mMax = m.max_marks ? parseFloat(m.max_marks) : 100;
                        totalMaxPossible += mMax;
                        marksRows += `
                            <tr>
                                <td>${m.subject ? m.subject.name : '{{ __("Unknown Subject") }}'}</td>
                                <td class="text-center font-weight-bold">${m.marks_obtained}</td>
                                <td class="text-center">${mMax}</td>
                                <td>${m.remarks || ''}</td>
                            </tr>
                        `;
                    });
                }
                
                $('#reportMarksList').html(marksRows);
                $('#reportTotalMarks').text(data.total_marks);
                $('#reportMaxMarks').text(totalMaxPossible);
                $('#reportPercentage').text(data.percentage.toFixed(2));
                
                let resultClass = data.percentage >= 40 ? 'badge-success' : 'badge-danger';
                let resultText = data.percentage >= 40 ? '{{ __("PASSED") }}' : '{{ __("FAILED") }}';
                if(data.total_marks == 0 && totalMaxPossible == 0) {
                     resultText = '{{ __("N/A") }}';
                     resultClass = 'badge-secondary';
                }
                
                $('#reportResult').removeClass('badge-success badge-danger badge-secondary').addClass(resultClass).text(resultText);
                
                $('#reportModal').modal('show');
            }).fail(function() {
                Swal.fire('{{ __("Error") }}', '{{ __("Failed to load report data.") }}', 'error');
            });
        });
    });
</script>
@stop
