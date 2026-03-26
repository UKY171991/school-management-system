@extends('adminlte::page')

@section('title', 'Exam Timetable')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ __('Exam Timetable Management') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Exam Timetable') }}</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Compact Filter & Action Bar -->
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body p-3">
            <form id="filterForm" class="row align-items-end g-3">
                <div class="col-md-2">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-school mr-1"></i> {{ __('School') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_school_id">
                        <option value="">{{ __('All Schools') }}</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-file-alt mr-1"></i> {{ __('Exam Type') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_exam_type" name="exam_type">
                        <option value="">{{ __('All Types') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-graduation-cap mr-1"></i> {{ __('Class') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_grade_id" name="grade_id">
                        <option value="">{{ __('All Classes') }}</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" data-school="{{ $grade->school_id }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-layer-group mr-1"></i> {{ __('Section') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_section_id" name="section_id">
                        <option value="">{{ __('All Sections') }}</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-grade="{{ $section->grade_id }}" data-school="{{ $section->grade ? $section->grade->school_id : '' }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Independent Print and Reset Buttons moved out or we can just hide the filter button but keep others?
                     The user said 'filter shuold be work live'. Usually 'Filter' button is the submit.
                     'Reset' and 'Print' might still be needed.
                     I will hide ONLY the Filter button but keep the container visible for other buttons.
                -->
                <div class="col-md-auto">
                     <!-- Filter button hidden -->
                    <button type="submit" class="btn btn-primary shadow-sm px-4 d-none">
                        <i class="fas fa-search mr-1"></i> {{ __('Filter') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary shadow-sm ml-1" id="resetFilter" title="{{ __('Reset Filter') }}">
                        <i class="fas fa-undo"></i>
                    </button>
                    <button type="button" class="btn btn-info shadow-sm ml-1 px-4" id="printTimetableBtn">
                        <i class="fas fa-print mr-1"></i> {{ __('Print') }}
                    </button>
                </div>
                <div class="col text-right">
                    <button type="button" class="btn btn-success shadow-sm px-4" id="newTimetableBtn">
                        <i class="fas fa-plus mr-1"></i> {{ __('Add Entry') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-calendar-check mr-2 text-primary"></i>{{ __('Exam Schedule') }}
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="timetableTable" style="width:100%">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-top-0">{{ __('Date') }}</th>
                            <th class="border-top-0">{{ __('School') }}</th>
                            <th class="border-top-0">{{ __('Time Slot') }}</th>
                            <th class="border-top-0">{{ __('Subject') }}</th>
                            <th class="border-top-0">{{ __('Exam Name') }}</th>
                            <th class="border-top-0">{{ __('Exam Type') }}</th>
                            <th class="border-top-0">{{ __('Class / Section') }}</th>
                            <th class="border-top-0">{{ __('Room') }}</th>
                            <th class="border-top-0 text-center" width="120">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="timetableList">
                        <!-- Loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Timetable Modal -->
<div class="modal fade" id="timetableModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form id="timetableForm">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="modalTitle">
                        <i class="fas fa-plus-circle mr-2"></i>{{ __('Add Exam Schedule') }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="timetable_id">
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-uppercase">{{ __('School') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2 shadow-none" id="modal_school_id" required style="width:100%">
                                    <option value="">{{ __('Select School...') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-uppercase">{{ __('Exam Type') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2 shadow-none" name="exam_type" id="modal_exam_type" required style="width:100%">
                                <option value="">{{ __('Select Exam Type...') }}</option>
                                <!-- Populated by JS -->
                            </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-uppercase">{{ __('Class') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="grade_id" id="modal_grade_id" required style="width: 100%;">
                                    <option value="">{{ __('Select Class...') }}</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}" data-school="{{ $grade->school_id }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-uppercase">{{ __('Section') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="section_id" id="section_id" required style="width: 100%;">
                                    <option value="">{{ __('Select Section...') }}</option>
                                    <!-- Sections populated by JS -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-uppercase">{{ __('Exam Date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control py-2 h-auto mt-0" name="exam_date" id="exam_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-uppercase">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                                <input type="time" class="form-control py-2 h-auto mt-0" name="start_time" id="start_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-uppercase">{{ __('End Time') }} <span class="text-danger">*</span></label>
                                <input type="time" class="form-control py-2 h-auto mt-0" name="end_time" id="end_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-uppercase">{{ __('Subject') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="subject_id" id="subject_id" required style="width: 100%;">
                                    <option value="">{{ __('Select Subject...') }}</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" data-school="{{ $subject->school_id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold small text-uppercase">{{ __('Room Number') }}</label>
                                <input type="text" class="form-control py-2 h-auto mt-0" name="room_number" id="room_number" placeholder="{{ __('e.g. Hall 1 / Room 102') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-info px-4 shadow-sm">{{ __('Save Entry') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .select2-container--bootstrap4 .select2-selection {
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
    #timetableTable thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #4a5568;
    }
    .badge-date {
        background-color: #f7fafc;
        color: #2d3748;
        border: 1px solid #e2e8f0;
    }
</style>
@stop

@section('js')
<script>
    const rawExamTypes = @json($examTypes);
    const rawGrades = @json($grades);
    const rawSections = @json($sections);

    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Initialize Select2
        // Initialize Select2 with dropdownParent for Modals
        $('#filterForm .select2').select2({ theme: 'bootstrap4' });
        $('#timetableModal .select2').select2({ 
            theme: 'bootstrap4',
            dropdownParent: $('#timetableModal')
        });

        function populateExamTypes(schoolId) {
            let typeSelect = $('#filter_exam_type');
            let currentType = typeSelect.val();
            
            typeSelect.empty().append('<option value="">{{ __("All Types") }}</option>');
            
            if (!schoolId) return; // Don't show types if no school selected
            
            let filteredTypes = rawExamTypes.filter(t => t.school_id == schoolId);

            filteredTypes.forEach(function(type) {
                let newOption = new Option(type.name, type.name, false, false);
                if(type.school_id) $(newOption).attr('data-school', type.school_id);
                typeSelect.append(newOption);
            });

            // Restore if valid
            if(currentType && typeSelect.find(`option[value='${currentType}']`).length > 0) {
                typeSelect.val(currentType);
            } else {
                typeSelect.val('');
            }
        }

        // Initialize Exam Types
        populateExamTypes('');

        function populateGrades(schoolId, targetSelector = '#filter_grade_id') {
            let gradeSelect = $(targetSelector);
            let currentGrade = gradeSelect.val();
            
            gradeSelect.empty().append('<option value="">{{ __("All Classes") }}</option>'); // Consistent default

            if (!schoolId && targetSelector === '#filter_grade_id') return; // Enforce school selection for filter

            let filteredGrades = schoolId ? rawGrades.filter(g => g.school_id == schoolId) : rawGrades;

            filteredGrades.forEach(function(grade) {
                let newOption = new Option(grade.name, grade.id, false, false);
                $(newOption).attr('data-school', grade.school_id);
                gradeSelect.append(newOption);
            });

            if(currentGrade && gradeSelect.find(`option[value='${currentGrade}']`).length > 0) {
                gradeSelect.val(currentGrade);
            } else {
                gradeSelect.val('');
            }
        }

        function populateSections(gradeId, targetSelector = '#filter_section_id', schoolId = null) {
            let sectionSelect = $(targetSelector);
            let currentSection = sectionSelect.val();
            
            sectionSelect.empty().append('<option value="">{{ __("All Sections") }}</option>');

            let filteredSections = [];
            
            if (gradeId) {
                filteredSections = rawSections.filter(s => s.grade_id == gradeId);
            } else if (schoolId) {
                filteredSections = rawSections.filter(s => s.grade && s.grade.school_id == schoolId);
            } else {
                filteredSections = rawSections;
            }

            filteredSections.forEach(function(section) {
                let gradeName = (section.grade) ? section.grade.name + ' - ' : '';
                // If filtering by specific grade (modal or filter with grade selected), just show section name
                // If showing all sections for a school (filter without grade), show Grade - Section
                let label = (targetSelector === '#filter_section_id' && !gradeId) ? gradeName + section.name : section.name;
                
                let newOption = new Option(label, section.id, false, false);
                // Store grade_id for reference if needed
                $(newOption).attr('data-grade', section.grade_id);
                sectionSelect.append(newOption);
            });

             if(currentSection && sectionSelect.find(`option[value='${currentSection}']`).length > 0) {
                sectionSelect.val(currentSection);
            } else {
                sectionSelect.val('');
            }
            
            // Re-init Select2 if this is the modal dropdown to ensure UI updates
            if(targetSelector === '#section_id') {
                sectionSelect.select2({ theme: 'bootstrap4', dropdownParent: $('#timetableModal') });
            }
        }

        function populateModalExamTypes(schoolId) {
            let typeSelect = $('#modal_exam_type');
            let currentType = typeSelect.val();
            
            typeSelect.empty().append('<option value="">{{ __("Select Exam Type...") }}</option>');
            
            let filteredTypes = [];

            if (schoolId) {
                filteredTypes = rawExamTypes.filter(t => t.school_id == schoolId);
            } else {
                 // If no school selected in modal (rare for Add, possible for Edit), show unique?
                 // Or better to force school selection.
                 // Let's show unique names like in filter.
                const seen = new Set();
                filteredTypes = rawExamTypes.filter(t => {
                    if(!t.name) return false;
                    const duplicate = seen.has(t.name);
                    seen.add(t.name);
                    return !duplicate;
                });
            }

            filteredTypes.forEach(function(type) {
                let newOption = new Option(type.name, type.name, false, false);
                if(type.school_id) $(newOption).attr('data-school', type.school_id);
                typeSelect.append(newOption);
            });
            
             if(currentType && typeSelect.find(`option[value='${currentType}']`).length > 0) {
                typeSelect.val(currentType);
            } else {
                typeSelect.val('');
            }
        }


        // Initialize DataTable
        let table = $('#timetableTable').DataTable({
            processing: true,
            language: { 
                search: "_INPUT_", 
                searchPlaceholder: "{{ __('Search exams...') }}",
                lengthMenu: "{{ __('Show _MENU_ entries') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                infoFiltered: "{{ __('(filtered from _MAX_ total entries)') }}",
                zeroRecords: "{{ __('No matching records found') }}",
                paginate: {
                    first: "{{ __('First') }}",
                    last: "{{ __('Last') }}",
                    next: "{{ __('Next') }}",
                    previous: "{{ __('Previous') }}"
                }
            },
            ajax: {
                url: "{{ route('exam-timetable.index') }}",
                data: function (d) {
                    d.school_id = $('#filter_school_id').val();
                    d.section_id = $('#filter_section_id').val();
                    d.grade_id = $('#filter_grade_id').val();
                    d.exam_type = $('#filter_exam_type').val();
                },
                dataSrc: ""
            },
            columns: [
                {
                    data: 'exam_date',
                    render: function(data) {
                        return `<span class="badge badge-date px-3 py-2 font-weight-normal shadow-xs"><i class="far fa-calendar-alt mr-1"></i> ${data}</span>`;
                    }
                },
                {
                    data: 'exam.school',
                    render: function(data, type, row) {
                        return data ? `<div class="font-weight-bold text-dark">${data.name}</div>` : '<span class="text-danger">N/A</span>';
                    }
                },
                {
                    render: function(data, type, row) {
                        return `<span class="text-primary small font-weight-bold"><i class="far fa-clock mr-1"></i> ${row.start_time} - ${row.end_time}</span>`;
                    }
                },
                {
                    data: 'subject',
                    render: function(data) {
                        return data ? `<div class="font-weight-500">${data.name}</div>` : '<span class="text-danger">N/A</span>';
                    }
                },
                {
                    data: 'exam',
                    render: function(data) {
                        let sessionText = data && data.session ? ` <small class="text-muted">[${data.session}]</small>` : '';
                        return data ? `<div class="text-dark small">${data.name}${sessionText}</div>` : 'N/A';
                    }
                },
                {
                    data: 'exam',
                    render: function(data) {
                        return data && data.type ? `<span class="badge badge-info px-2">${data.type}</span>` : '-';
                    }
                },
                {
                    render: function(data, type, row) {
                        let gradeName = (row.section && row.section.grade) ? row.section.grade.name : '';
                        let sectionName = row.section ? row.section.name : 'N/A';
                        return `<span class="text-muted small">${gradeName} - ${sectionName}</span>`;
                    }
                },
                { data: 'room_number', defaultContent: '-' },
                {
                    data: 'id',
                    className: 'text-center',
                    render: function (data) {
                        return `
                            <div class="btn-group">
                                <button class="btn btn-outline-warning btn-sm border-0 editTimetable" data-id="${data}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm border-0 deleteTimetable" data-id="${data}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            rowId: function(a) { return 'timetable_' + a.id; },
            dom: '<"d-flex justify-content-between mb-3"lf>rt<"d-flex justify-content-between mt-3"ip>'
        });

        // Store original Exam Type options
        let allExamTypes = [];
        $('#filter_exam_type option').each(function() {
            if($(this).val()) {
                allExamTypes.push({
                    value: $(this).val(),
                    text: $(this).text(),
                    school: $(this).data('school')
                });
            }
        });

        // Enforce "Select One By One" logic with alerts
        $('#filter_exam_type, #filter_grade_id').on('select2:opening', function(e) {
            if (!$('#filter_school_id').val()) {
                e.preventDefault();
                Swal.fire('{{ __("Sequence Error") }}', '{{ __("Please select a School first.") }}', 'warning');
            }
        });

        $('#filter_section_id').on('select2:opening', function(e) {
            if (!$('#filter_grade_id').val()) {
                 // But wait, user might want to see all sections of a school? 
                 // User said "show alert if missing any fields" and "select one by one".
                 // This implies hierarchy: School -> Class -> Section.
                e.preventDefault();
                Swal.fire('{{ __("Sequence Error") }}', '{{ __("Please select a Class first.") }}', 'warning');
            }
        });

        $('#filter_school_id').change(function() {
            let schoolId = $(this).val();
            
            populateExamTypes(schoolId);
            populateGrades(schoolId, '#filter_grade_id');
            // When school changes, clear section and grade selection in filter
            // populateSections logic handles the list content, but we should reset value
            $('#filter_grade_id').val('').trigger('change.select2'); // This handles sections via grade change listener? 
                                                                     // No, grade change listener is separate.
            
            // Actually, we need to clear Sections too because Class changed/cleared
            populateSections(null, '#filter_section_id', schoolId); 
            
            // Trigger table reload
            table.ajax.reload();
        });

        $('#filter_grade_id').change(function() {
            let gradeId = $(this).val();
            populateSections(gradeId, '#filter_section_id');
            table.ajax.reload();
        });
        
        // Initial check
        if($('#filter_school_id').val()) {
             $('#filter_school_id').trigger('change');
        }

        $('#filter_section_id, #filter_exam_type').change(function() {
            table.ajax.reload();
        });

        $('#filterForm').submit(function(e) { e.preventDefault(); table.ajax.reload(); });
        $('#resetFilter').click(function() { 
            // Check if school is selected
            if($('#filter_school_id').val()) {
                // Trigger change to empty, this will cascade via the change listener
                // clearing exam types, grades, sections, and reloading table
                $('#filter_school_id').val('').trigger('change');
            } else {
                // School is already empty, so manually ensure everything else is cleared
                $('#filter_exam_type').val('').trigger('change');
                $('#filter_grade_id').val('').trigger('change');
                $('#filter_section_id').val('').trigger('change');
                
                // Clear dropdown lists to default state
                populateExamTypes('');
                populateGrades('', '#filter_grade_id');
                populateSections(null, '#filter_section_id', '');
                
                table.ajax.reload();
            }
        });

        $('#printTimetableBtn').click(function() {
            let params = {
                print: 1,
                school_id: $('#filter_school_id').val(),
                exam_type: $('#filter_exam_type').val(),
                grade_id: $('#filter_grade_id').val(),
                section_id: $('#filter_section_id').val()
            };
            
            let queryString = $.param(params);
            window.open("{{ route('exam-timetable.index') }}?" + queryString, '_blank');
        });

        $('#modal_school_id').change(function() {
            let schoolId = $(this).val();
            
            populateModalExamTypes(schoolId);
            populateGrades(schoolId, '#modal_grade_id');
            populateSections(null, '#section_id', schoolId);
            
            // Re-init dependent select2s only (avoid re-initing self)
            $('#modal_exam_type, #modal_grade_id, #section_id, #subject_id').select2({ 
                theme: 'bootstrap4', 
                dropdownParent: $('#timetableModal') 
            });
        });


        // Enforce "Select One By One" logic for Modal
        $('#modal_exam_type, #modal_grade_id, #subject_id').on('select2:opening', function(e) {
            if (!$('#modal_school_id').val()) {
                e.preventDefault();
                Swal.fire('Sequence Error', 'Please select a School first.', 'warning');
            }
        });

        $('#section_id').on('select2:opening', function(e) {
            if (!$('#modal_grade_id').val()) {
                e.preventDefault();
                Swal.fire('Sequence Error', 'Please select a Class first.', 'warning');
            }
        });

        $(document).on('click', '#newTimetableBtn', function() {
            $('#timetableForm').trigger("reset");
            $('#timetableForm .select2').val('').trigger('change');
            $('#timetable_id').val('');
            $('#modalTitle').html('<i class="fas fa-plus-circle mr-2"></i>{{ __("Add Exam Schedule") }}');
            $('#modal_school_id').prop('disabled', false); 
            
            // Clear dependent dropdowns
            $('#modal_grade_id').val('').trigger('change');
            $('#section_id').empty().append('<option value="">{{ __("Select Class First") }}</option>');
            
            $('#timetableModal').modal('show');
        });

        $('#timetableForm').submit(function(e) {
            e.preventDefault();
            let id = $('#timetable_id').val();
            let url = id ? `/admin/exam-timetable/${id}` : "{{ route('exam-timetable.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#timetableModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ title: '{{ __("Success!") }}', text: data.success, type: 'success', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                },
                error: function(xhr) {
                   if(xhr.status === 422) {
                       let errors = xhr.responseJSON.errors;
                       let msg = '';
                       $.each(errors, function(key, val){ msg += val[0] + '<br>'; });
                       Swal.fire('{{ __("Validation Error") }}', msg, 'error');
                   } else {
                       Swal.fire('{{ __("Error") }}', '{{ __("Something went wrong") }}', 'error');
                   }
                }
            });
        });

        $('body').on('click', '.editTimetable', function() {
            let id = $(this).data('id');
            $.get(`/admin/exam-timetable/${id}`, function(data) {
                $('#timetable_id').val(data.id);
                
                // Set School first
                if(data.exam && data.exam.school_id) {
                    $('#modal_school_id').val(data.exam.school_id);
                    // Manually populate dependents to ensure sync
                    populateModalExamTypes(data.exam.school_id);
                    populateGrades(data.exam.school_id, '#modal_grade_id');
                } else {
                     $('#modal_school_id').val(''); 
                     populateModalExamTypes('');
                     populateGrades('', '#modal_grade_id');
                }
                
                if(data.exam && data.exam.type) {
                    $('#modal_exam_type').val(data.exam.type);
                }
                
                if(data.section && data.section.grade_id) {
                    $('#modal_grade_id').val(data.section.grade_id);
                    // Populate sections specifically for this grade
                    populateSections(data.section.grade_id, '#section_id');
                } else {
                    // Fallback if no grade (shouldn't happen for valid data)
                    if(data.exam && data.exam.school_id) {
                        populateSections(null, '#section_id', data.exam.school_id);
                    }
                }

                $('#section_id').val(data.section_id);
                
                $('#subject_id').val(data.subject_id).trigger('change');
                
                // Ensure date is YYYY-MM-DD format
                let dateVal = data.exam_date;
                if(dateVal && dateVal.length > 10) dateVal = dateVal.substring(0, 10);
                $('#exam_date').val(dateVal);
                
                // If Flatpickr is active, update it
                let fp = document.querySelector('#exam_date')._flatpickr;
                if(fp) {
                    fp.setDate(dateVal);
                }

                $('#start_time').val(data.start_time);
                $('#end_time').val(data.end_time);
                $('#room_number').val(data.room_number);
                $('#modalTitle').html('<i class="fas fa-edit mr-2"></i>{{ __("Edit Exam Schedule") }}');
                
                // Final Re-init of all select2s to ensure visual state matches values
                $('#timetableModal .select2').select2({ theme: 'bootstrap4', dropdownParent: $('#timetableModal') });
                
                $('#timetableModal').modal('show');
            });
        });

        $('body').on('click', '.deleteTimetable', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: '{{ __("Remove this entry?") }}',
                text: '{{ __("This action cannot be undone.") }}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("Yes, remove it!") }}'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/admin/exam-timetable/${id}`,
                        type: "DELETE",
                        success: function(data) {
                            table.ajax.reload();
                            Swal.fire({ title: '{{ __("Removed!") }}', type: 'success', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                        }
                    });
                }
            });
        });
    });
</script>
@stop
