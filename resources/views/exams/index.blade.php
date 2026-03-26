@extends('adminlte::page')

@section('title', __('Exam Schedule'))

@section('content_header')
    <h1>{{ __('Exam Schedule') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
    <!-- Filter Bar -->
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body p-3">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-university mr-1"></i> {{ __('Filter by School') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_school_id">
                        <option value="">{{ __('All Schools') }}</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="button" class="btn btn-outline-secondary shadow-sm" id="resetFilter">
                        <i class="fas fa-undo"></i> {{ __('Reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-danger">
        <div class="card-header border-0">
            <h3 class="card-title font-weight-bold">{{ __('Registered Exams') }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-danger btn-sm shadow-sm" id="newExamBtn">
                    <i class="fas fa-plus mr-1"></i> {{ __('Schedule Exam') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="examsTable">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('Exam Name') }}</th>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Session') }}</th>
                            <th>{{ __('Class') }}</th>
                            <th>{{ __('Dates') }}</th>
                            <th width="150" class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="examList"></tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Exam Modal -->
<div class="modal fade" id="examModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="examForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Schedule Exam') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="exam_id">
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
                        <label>{{ __('Exam Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="{{ __('e.g. First Term Exam') }}">
                    </div>

                    <div class="form-group">
                        <label>{{ __('Exam Type') }}</label>
                        <select class="form-control select2" name="type" id="type" style="width: 100%;">
                            <option value="">{{ __('Select Type') }}</option>
                            @foreach($examTypes as $type)
                                <option value="{{ $type->name }}" data-school="{{ $type->school_id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Session') }}</label>
                        <select class="form-control select2" name="session" id="session" style="width: 100%;">
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 10;
                                $endYear = $currentYear + 10;
                                // Simple logic for current academic session: if Jan-May, current-1, else current
                                $activeYear = (date('n') <= 5) ? $currentYear - 1 : $currentYear;
                                $activeSession = $activeYear . '-' . substr($activeYear + 1, -2);
                            @endphp
                            @for($i = $startYear; $i <= $endYear; $i++)
                                @php 
                                    $sessionVal = $i . '-' . substr($i + 1, -2);
                                @endphp
                                <option value="{{ $sessionVal }}" {{ $sessionVal == $activeSession ? 'selected' : '' }}>
                                    {{ $sessionVal }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Class (Optional)') }}</label>
                        <select class="form-control select2" name="grade_id" id="grade_id" style="width: 100%;">
                            <option value="">{{ __('All Classes') }}</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('Leave blank if this exam applies to multiple classes.') }}</small>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Start Date') }}</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('End Date') }}</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Save Exam') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Modal -->
<div class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="generateForm">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Generate Timetable') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="exam_id" id="gen_exam_id">
                    
                    <div class="form-group">
                        <label>{{ __('Select Class & Section') }}</label>
                        <select class="form-control select2" name="section_id" required style="width: 100%;">
                            <option value="">{{ __('Select Section') }}</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->grade ? $section->grade->name : __('N/A') }} - {{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Select Subjects') }}</label>
                        <select class="form-control select2" name="subject_ids[]" multiple="multiple" required style="width: 100%;">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" selected>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Start From (Date)') }}</label>
                        <input type="date" class="form-control" name="start_date" id="gen_start_date" required>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Room / Hall Number') }}</label>
                        <input type="text" class="form-control" name="room_number" placeholder="{{ __('e.g. Hall 1, Room 102') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Generate') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="printForm">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Print Timetable') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="exam_id" id="print_exam_id">
                    
                    <div class="form-group">
                        <label>{{ __('Select Section') }}</label>
                        <select class="form-control select2" name="section_id" id="print_section_id" required style="width: 100%;">
                            <option value="">{{ __('Select Section') }}</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->grade ? $section->grade->name : __('N/A') }} - {{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-info">{{ __('Print') }}</button>
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
        // Initialize Select2 for Filters (No parent)
        $('#filter_school_id').select2();

        // Initialize Select2 for Modals (With parent)
        $('#examModal .select2').select2({ dropdownParent: $('#examModal') });
        $('#generateModal .select2').select2({ dropdownParent: $('#generateModal') });
        $('#printModal .select2').select2({ dropdownParent: $('#printModal') });

        // Auto-populate Exam Name from Exam Type
        $('#type').on('change', function() {
            let val = $(this).val();
            if (val) {
                $('#name').val(val);
            }
        });

        // Filter Handlers
        $('#filter_school_id').on('change', function() {
            loadExams();
        });

        $('#resetFilter').click(function() {
            $('#filter_school_id').val('').trigger('change');
        });


        // Initialize DataTable
        let table = $('#examsTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('exams.index') }}",
                data: function (d) {
                    d.school_id = $('#filter_school_id').val();
                },
                dataSrc: ""
            },
            columns: [
                {
                    data: 'name',
                    render: function(data, type, row) {
                        return `<div class="font-weight-bold text-danger">${data}</div>
                                ${row.type ? `<span class="badge badge-secondary small">${row.type}</span>` : ''}`;
                    }
                },
                {
                    data: 'school',
                    render: function(data) {
                        return data ? `<span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-school mr-1"></i>${data.name}</span>` : '<span class="text-muted">N/A</span>';
                    }
                },
                {
                    data: 'session',
                    render: function(data) {
                        return `<span class="badge badge-info px-2 py-1">${data || '-'}</span>`;
                    }
                },
                {
                    data: 'grade',
                    render: function(data) {
                        return data ? data.name : '<span class="badge badge-info">' + "{{ __('All Classes') }}" + '</span>';
                    }
                },
                {
                    render: function(data, type, row) {
                        return `<div class="small">
                                    <div><i class="far fa-calendar-check mr-1 text-success"></i>${row.start_date}</div>
                                    <div><i class="far fa-calendar-times mr-1 text-danger"></i>${row.end_date}</div>
                                </div>`;
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-xs generateTimetable" data-id="${data}" data-start="${row.start_date}" title="Generate Timetable"><i class="fas fa-magic"></i></button>
                                <button class="btn btn-outline-info btn-xs printTimetable" data-id="${data}" title="Print Timetable"><i class="fas fa-print"></i></button>
                                <button class="btn btn-outline-warning btn-xs editExam" data-id="${data}" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-xs deleteExam" data-id="${data}" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            rowId: function(a) { return 'exam_' + a.id; },
            order: [[ 4, "desc" ]] // Order by Start Date
        });

        function loadExams() {
            table.ajax.reload();
        }

        // loadExams calls are now handled by table.ajax.reload() in filter change, 
        // but initial load is handled by DataTable init.
        // We can keep loadExams as a wrapper for reload.

        // Store original options for filtering
        let allExamTypes = [];
        $('#type option').each(function() {
            if($(this).val()) {
                allExamTypes.push({
                    value: $(this).val(),
                    text: $(this).text(),
                    school: $(this).data('school')
                });
            }
        });

        $('#school_id').change(function() {
            let schoolId = $(this).val();
            let typeSelect = $('#type');
            let currentVal = typeSelect.val();

            // Clear current options, adding back the default placeholder
            typeSelect.empty().append('<option value="">Select Type</option>');
            
            if(schoolId) {
                // Filter and append valid options
                allExamTypes.forEach(function(type) {
                    if(!type.school || type.school == schoolId) {
                        let newOption = new Option(type.text, type.value, false, false);
                        $(newOption).attr('data-school', type.school);
                        typeSelect.append(newOption);
                    }
                });
            } else {
                // If no school selected (or "Choose School..."), maybe show none or all? 
                // Let's show all to be safe, or maybe none until school is picked.
                // User requirement: "show only selected schools".
                // So if no school, show all? Or show none?
                // Usually logic is: Select School First. So showing all is fine, or empty.
                // Let's reload all so user can see what's available if they haven't picked a school yet (optional)
                // But better to enforce school selection. 
                // Let's just restore all for now.
                allExamTypes.forEach(function(type) {
                    let newOption = new Option(type.text, type.value, false, false);
                    $(newOption).attr('data-school', type.school);
                    typeSelect.append(newOption);
                });
            }
            
            // Restore previous value if it's still valid
            if (currentVal && typeSelect.find(`option[value='${currentVal}']`).length > 0) {
                 typeSelect.val(currentVal);
            } else {
                 typeSelect.val('');
            }

            typeSelect.trigger('change'); 
        });

        $('#newExamBtn').click(function() {
            $('#examForm').trigger("reset");
            $('#exam_id').val('');
            $('#grade_id').val('').trigger('change');
            $('#type').val('').trigger('change');
            $('#school_id').val('').trigger('change');
            // Trigger change for session to ensure select2 reflects the default or empty
            $('#session').trigger('change');
            $('#modalTitle').text("{{ __('Schedule Exam') }}");
            $('#examModal').modal('show');
        });

        $('#examForm').submit(function(e) {
            e.preventDefault();
            let id = $('#exam_id').val();
            let url = id ? `/admin/exams/${id}` : "{{ route('exams.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#examModal').modal('hide');
                    loadExams();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(data) {
                    Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                }
            });
        });

        $('body').on('click', '.generateTimetable', function() {
            let id = $(this).data('id');
            let start = $(this).data('start');
            $('#gen_exam_id').val(id);
            $('#gen_start_date').val(start);
            $('#generateModal').modal('show');
        });

        $('#generateForm').submit(function(e) {
            e.preventDefault();
            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> ' + "{{ __('Generating...') }}");
            
            $.ajax({
                url: "{{ route('exams.generate') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    btn.prop('disabled', false).text("{{ __('Generate') }}");
                    $('#generateModal').modal('hide');
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text("{{ __('Generate') }}");
                    Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                }
            });
        });

        $('body').on('click', '.printTimetable', function() {
            let id = $(this).data('id');
            $('#print_exam_id').val(id);
            $('#print_section_id').val('').trigger('change');
            $('#printModal').modal('show');
        });

        $('#printForm').submit(function(e) {
            e.preventDefault();
            let exam_id = $('#print_exam_id').val();
            let section_id = $('#print_section_id').val();
            let url = "{{ route('exam-timetable.index') }}?print=1&exam_id=" + exam_id + "&section_id=" + section_id;
            window.open(url, '_blank');
            $('#printModal').modal('hide');
        });

        $('body').on('click', '.editExam', function() {
            let id = $(this).data('id');
            $.get(`/admin/exams/${id}`, function(data) {
                $('#exam_id').val(data.id);
                $('#name').val(data.name);
                $('#type').val(data.type).trigger('change');
                $('#session').val(data.session).trigger('change');
                $('#school_id').val(data.school_id).trigger('change');
                $('#grade_id').val(data.grade_id).trigger('change');
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);
                $('#modalTitle').text("{{ __('Edit Exam') }}");
                $('#examModal').modal('show');
            });
        });

        $('body').on('click', '.deleteExam', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this exam schedule?') }}")) {
                $.ajax({
                    url: `/admin/exams/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#exam_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
