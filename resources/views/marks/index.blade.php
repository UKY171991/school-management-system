@extends('adminlte::page')

@section('title', __('Exam Marks Entry'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ __('Student Marks Entry') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Marks Entry') }}</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Selection Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-filter mr-2 text-primary"></i>{{ __('Select Criteria') }}
            </h3>
        </div>
        <div class="card-body bg-light">
            <form id="criteriaForm" class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">{{ __('School') }}</label>
                    <select class="form-control select2 shadow-none" id="school_id" name="school_id" required>
                        <option value="">{{ __('Choose School...') }}</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">{{ __('Exam Type') }}</label>
                    <select class="form-control select2 shadow-none" id="exam_type_id" name="exam_type_id" required disabled>
                        <option value="">{{ __('Choose Exam Type...') }}</option>
                        @foreach(\App\Models\ExamType::all() as $type)
                            <option value="{{ $type->id }}" data-school="{{ $type->school_id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">{{ __('Class / Section') }}</label>
                    <select class="form-control select2 shadow-none" id="section_id" name="section_id" required disabled>
                        <option value="">{{ __('Choose Section...') }}</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-school="{{ $section->school_id }}" data-grade="{{ $section->grade_id }}">
                                {{ $section->grade ? $section->grade->name : 'N/A' }} - {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">{{ __('Subject') }}</label>
                    <select class="form-control select2 shadow-none" id="subject_id" name="subject_id" required disabled>
                        <option value="">{{ __('Choose Subject...') }}</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" data-school="{{ $subject->school_id }}" data-grade="{{ $subject->grade_id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </select>
                </div>


                <div class="col text-right">
                     <button type="submit" class="btn btn-primary shadow-sm px-4" id="loadBtn">
                        <i class="fas fa-users mr-1"></i> {{ __('Load Students') }}
                    </button>
                    <button type="button" class="btn btn-outline-danger shadow-sm ml-2" id="viewHistoryBtn">
                        <i class="fas fa-history mr-1"></i> {{ __('View History') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary shadow-sm ml-2" id="resetBtn">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div id="examInfoCard" class="card shadow-sm border-0 mb-4 bg-light d-none">
        <div class="card-body p-0">
            <div class="row m-0 text-center">
                <div class="col-md-3 border-right py-3 bg-white">
                    <div class="small text-muted text-uppercase mb-1 font-weight-bold">{{ __('Subject Exam') }}</div>
                    <div class="h6 mb-0 font-weight-bold text-dark" id="display_subject">English</div>
                </div>
                <div class="col-md-3 border-right py-3 bg-white">
                    <div class="small text-muted text-uppercase mb-1 font-weight-bold">{{ __('Maximum Marks (Pass)') }}</div>
                    <div class="h6 mb-0 font-weight-bold text-dark">
                        <input type="number" class="form-control form-control-sm d-inline-block text-center shadow-none border-primary" id="global_max_marks" value="100" style="width: 70px;">
                        <span class="small text-muted ml-1">({{ __('Pass') }}: <span id="display_pass_marks">33</span>)</span>
                    </div>
                </div>
                <div class="col-md-3 border-right py-3 bg-white">
                    <div class="small text-muted text-uppercase mb-1 font-weight-bold">{{ __('Class / Section') }}</div>
                    <div class="h6 mb-0 font-weight-bold text-dark" id="display_class">Grade 03 - A</div>
                </div>
                <div class="col-md-3 py-3 bg-white">
                    <div class="small text-muted text-uppercase mb-1 font-weight-bold">{{ __('Exam Schedule') }}</div>
                    <div class="h6 mb-0 font-weight-bold text-danger" id="display_exam">First Term</div>
                </div>
            </div>
        </div>
    </div>


    <!-- Marks Entry Table -->
    <div id="entryCard" class="card shadow-sm border-0 d-none">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-pen-alt mr-2 text-success"></i>{{ __('Enter Marks for Students') }}
            </h3>
        </div>
        <div class="card-body p-0">
            <form id="bulkMarkForm">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped m-0" id="marksEntryTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="60" class="text-center">{{ __('No.') }}</th>
                                <th width="200">{{ __('Roll No.') }}</th>
                                <th>{{ __('Student Name') }}</th>
                                <th width="100" class="text-center">{{ __('Absent') }}</th>
                                <th width="150" class="text-center">{{ __('Marks Obtain') }}</th>
                                <th width="150" class="text-center">{{ __('Result') }}</th>
                                <th>{{ __('Remarks') }}</th>
                            </tr>
                        </thead>
                        <tbody id="studentMarkList">
                            <!-- Students loaded here -->
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-light text-right">
                    <div class="small text-muted mb-3 italic">
                        <i class="fas fa-info-circle mr-1"></i> {{ __('Confirm all entries before saving. Existing marks for this exam/subject will be updated.') }}
                    </div>
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm" id="saveBulkBtn">
                        <i class="fas fa-save mr-2"></i>{{ __('Save All Marks') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Table (Hidden by default) -->
    <div id="historyCard" class="card shadow-sm border-0 d-none">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-history mr-2 text-danger"></i>{{ __('Recorded Marks History') }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" id="closeHistory"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="historyTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>{{ __('Student') }}</th>
                            <th>{{ __('Exam') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Obtain') }}</th>
                            <th>{{ __('Max') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="historyList"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="text-center py-5">
        <div class="display-4 text-muted mb-3"><i class="fas fa-clipboard-list"></i></div>
        <h4 class="text-muted">{{ __('Select filter criteria above and click Load Students') }}</h4>
        <p class="text-muted small">{{ __('You can manage all examination marks from this single interface.') }}</p>
    </div>
</div>

<style>
    .select2-container--bootstrap4 .select2-selection {
        border-radius: 4px !important;
        height: 38px !important;
        border: 1px solid #ced4da !important;
    }
    #marksEntryTable thead th, #historyTable thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #4a5568;
        border-top: none;
    }
    .mark-input {
        width: 100px;
        border-radius: 4px;
        text-align: center;
        font-weight: 600;
    }
    .mark-input:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40,167,69,.25);
    }
    .absent-check {
        transform: scale(1.5);
    }
    .result-badge {
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
</style>
@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

        // Backup all original options to ensure clean filtering
        const masterExams = $('#exam_type_id').find('option').clone();
        const masterSections = $('#section_id').find('option').clone();
        const masterSubjects = $('#subject_id').find('option').clone();

        // Targeted Filtering Logic
        $('#school_id').change(function() {
            const schoolId = $(this).val();
            console.log('School changed to:', schoolId);
            
            // 1. Rebuild Exam Type List
            $('#exam_type_id').empty().append('<option value="">{{ __("Choose Exam Type...") }}</option>');
            if (schoolId) {
                masterExams.each(function() {
                    let s = $(this).attr('data-school');
                    if ($(this).val() && String(s) === String(schoolId)) {
                        $('#exam_type_id').append($(this).clone());
                    }
                });
                $('#exam_type_id').prop('disabled', false);
            } else {
                $('#exam_type_id').prop('disabled', true);
            }

            // 2. Rebuild Section List
            $('#section_id').empty().append('<option value="">{{ __("Choose Section...") }}</option>');
            if (schoolId) {
                masterSections.each(function() {
                    let s = $(this).attr('data-school');
                    if ($(this).val() && String(s) === String(schoolId)) {
                        $('#section_id').append($(this).clone());
                    }
                });
                $('#section_id').prop('disabled', false);
            } else {
                $('#section_id').prop('disabled', true);
            }

            // 3. Reset Subject List
            $('#subject_id').empty().append('<option value="">{{ __("Choose Subject...") }}</option>').prop('disabled', true);

            // Refesh Select2
            $('#exam_type_id, #section_id, #subject_id').trigger('change').select2({ theme: 'bootstrap4', width: '100%' });
        });


        $('#section_id').change(function() {
            const sectionId = $(this).val();
            const schoolId = $('#school_id').val();
            const selectedOption = $(this).find('option:selected');
            const gradeId = selectedOption.attr('data-grade');

            console.log('Filtering subjects for School:', schoolId, 'Grade:', gradeId);

            // 4. Rebuild Subject List
            $('#subject_id').empty().append('<option value="">{{ __("Choose Subject...") }}</option>');
            
            if (sectionId && schoolId) {
                let matchCount = 0;
                masterSubjects.each(function() {
                    const s = $(this).attr('data-school');
                    const g = $(this).attr('data-grade');
                    const val = $(this).val();

                    if (val && String(s) === String(schoolId) && String(g) === String(gradeId)) {
                        $('#subject_id').append($(this).clone());
                        matchCount++;
                    }
                });
                console.log('Found ' + matchCount + ' subjects matching criteria.');
                $('#subject_id').prop('disabled', false);
            } else {
                $('#subject_id').prop('disabled', true);
            }

            // Refresh Select2
            $('#subject_id').trigger('change').select2({ theme: 'bootstrap4', width: '100%' });
        });








        $('#global_max_marks').on('input', function() {
            let val = parseFloat($(this).val()) || 0;
            $('#display_pass_marks').text(Math.ceil(val * 0.33));
            $('.mark-input').trigger('input'); // Refresh pass/fail status
        });

        // Load Students Handler
        $('#criteriaForm').submit(function(e) {
            e.preventDefault();
            let school_id = $('#school_id').val();
            let exam_type_id = $('#exam_type_id').val();
            let section_id = $('#section_id').val();
            let subject_id = $('#subject_id').val();

            $('#loadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

            $.get("{{ route('marks.index') }}", {
                fetch_students: 1,
                section_id: section_id,
                exam_type_id: exam_type_id,
                subject_id: subject_id
            }, function(data) {


                $('#loadBtn').prop('disabled', false).html('<i class="fas fa-users mr-1"></i> Load Students');
                
                if (data.length === 0) {
                    Swal.fire('{{ __("No Students Found") }}', '{{ __("This class/section has no registered students.") }}', 'info');
                    return;
                }

                // Update Info Card
                $('#display_subject').text($('#subject_id option:selected').text());
                $('#display_class').text($('#section_id option:selected').text());
                $('#display_exam').text($('#exam_type_id option:selected').text());




                // Build Table
                let rows = '';
                data.forEach((s, index) => {
                    let isAbsent = s.is_absent ? 'checked' : '';
                    let marksVal = s.marks_obtained !== null ? s.marks_obtained : '';
                    let max = parseFloat($('#global_max_marks').val()) || 100;
                    let passMarks = Math.ceil(max * 0.33);
                    let resultText = '-';
                    let resultClass = 'bg-light text-muted';

                    if (marksVal !== '' && !s.is_absent) {
                        if (parseFloat(marksVal) >= passMarks) {
                            resultText = '{{ __("PASS") }}';
                            resultClass = 'badge-success';
                        } else {
                            resultText = '{{ __("FAIL") }}';
                            resultClass = 'badge-danger';
                        }
                    } else if (s.is_absent) {
                        resultText = '{{ __("ABSENT") }}';
                        resultClass = 'badge-danger';
                    }

                    rows += `
                        <tr data-student-id="${s.id}">
                            <td class="text-center text-muted small">${index + 1}</td>
                            <td class="font-weight-500">${s.roll_number}</td>
                            <td class="font-weight-bold">${s.name}</td>
                            <td class="text-center">
                                <input type="checkbox" class="absent-check" name="absent[${s.id}]" ${isAbsent}>
                            </td>
                            <td class="text-center">
                                <input type="number" step="0.01" class="form-control form-control-sm mark-input m-auto" 
                                    name="marks[${s.id}]" value="${marksVal}" ${s.is_absent ? 'disabled' : ''}>
                            </td>
                            <td class="text-center">
                                <span class="badge ${resultClass} result-badge">${resultText}</span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="remarks[${s.id}]" value="${s.remarks || ''}" placeholder="{{ __('Comment...') }}">
                            </td>
                        </tr>
                    `;
                });

                $('#studentMarkList').html(rows);
                $('#emptyState, #historyCard').addClass('d-none');
                $('#examInfoCard, #entryCard').removeClass('d-none');

                // Logic for absent checkbox
                $('.absent-check').off('change').on('change', function() {
                    let input = $(this).closest('tr').find('.mark-input');
                    let badge = $(this).closest('tr').find('.result-badge');
                    if($(this).is(':checked')) {
                        input.prop('disabled', true).val('');
                        badge.text('{{ __("ABSENT") }}').removeClass('badge-success bg-light text-muted').addClass('badge-danger');
                    } else {
                        input.prop('disabled', false);
                        badge.text('-').removeClass('badge-danger').addClass('bg-light text-muted');
                    }
                });

                // Logic for result badge update on input
                $('.mark-input').off('input').on('input', function() {
                    let val = parseFloat($(this).val());
                    let max = parseFloat($('#global_max_marks').val()) || 100;
                    let pass = Math.ceil(max * 0.33);
                    let badge = $(this).closest('tr').find('.result-badge');

                    if (isNaN(val)) {
                        badge.text('-').removeClass('badge-success badge-danger').addClass('bg-light text-muted');
                    } else if (val > max) {
                        $(this).addClass('is-invalid');
                        badge.text('{{ __("INVALID") }}').addClass('badge-warning');
                    } else {
                        $(this).removeClass('is-invalid');
                        if (val >= pass) {
                            badge.text('{{ __("PASS") }}').removeClass('badge-danger bg-light text-muted').addClass('badge-success');
                        } else {
                            badge.text('{{ __("FAIL") }}').removeClass('badge-success bg-light text-muted').addClass('badge-danger');
                        }
                    }
                });
            });
        });

        // Save Bulk Handler
        $('#bulkMarkForm').submit(function(e) {
            e.preventDefault();
            let processedMarks = {};
            let processedRemarks = {};
            
            // Iterate through rows to capture all data including disabled (absent) inputs
            $('#studentMarkList tr').each(function() {
                let studentId = $(this).data('student-id');
                let isAbsent = $(this).find('.absent-check').is(':checked');
                let marksVal = $(this).find('.mark-input').val();
                let remarksVal = $(this).find('input[name^="remarks"]').val();

                processedMarks[studentId] = isAbsent ? null : marksVal;
                processedRemarks[studentId] = remarksVal;
            });

            $('#saveBulkBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> {{ __("Saving...") }}');

            $.ajax({
                url: "{{ route('marks.store') }}",
                type: "POST",
                data: {
                    bulk: 1,
                    school_id: $('#school_id').val(),
                    exam_type_id: $('#exam_type_id').val(),
                    subject_id: $('#subject_id').val(),
                    max_marks: $('#global_max_marks').val(),
                    marks: processedMarks,
                    remarks: processedRemarks
                },




                success: function(data) {
                    $('#saveBulkBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>{{ __("Save All Marks") }}');
                    Swal.fire({ title: '{{ __("Success!") }}', text: data.success, type: 'success', timer: 2000, showConfirmButton: false });
                }
            });
        });

        // View History Logic
        $('#viewHistoryBtn').click(function() {
            let schoolId = $('#school_id').val();
            $('#viewHistoryBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> ...');
            
            $.get("{{ route('marks.index') }}", { school_id: schoolId }, function(data) {
                $('#viewHistoryBtn').prop('disabled', false).html('<i class="fas fa-history mr-1"></i> View History');
                let rows = '';
                data.forEach(m => {
                    rows += `
                        <tr id="mark_${m.id}">
                            <td>${m.student.name}</td>
                            <td>${m.exam_type ? m.exam_type.name : '-'}</td>
                            <td>${m.subject.name}</td>
                            <td class="font-weight-bold text-success">${m.marks_obtained}</td>
                            <td class="text-muted">${m.max_marks}</td>
                            <td>
                                <button class="btn btn-xs btn-danger deleteMark" data-id="${m.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#historyList').html(rows);
                $('#emptyState, #entryCard, #examInfoCard').addClass('d-none');
                $('#historyCard').removeClass('d-none');
            });
        });

        $('#closeHistory').click(function() {
            $('#historyCard').addClass('d-none');
            $('#emptyState').removeClass('d-none');
        });

        $('body').on('click', '.deleteMark', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Remove this entry?') }}")) {
                $.ajax({
                    url: `/admin/marks/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#mark_${id}`).fadeOut();
                    }
                });
            }
        });

        $('#resetBtn').click(function() {
            $('#criteriaForm').trigger('reset');
            $('.select2').val('').trigger('change');
            $('#entryCard, #examInfoCard, #historyCard').addClass('d-none');
            $('#emptyState').removeClass('d-none');
        });
    });
</script>
@stop


