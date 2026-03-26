@extends('adminlte::page')

@section('title', __('Student Admit Cards'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ __('Student Admit Cards') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Admit Cards') }}</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary shadow-lg border-0">
            <div class="card-header bg-white py-3">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-id-card-alt mr-2"></i>{{ __('Generate Admit Cards') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-4 p-3 bg-light rounded shadow-sm mx-0">
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
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted text-uppercase mb-1">{{ __('Filter by Class') }}</label>
                            <select id="filter_grade" class="form-control select2">
                                <option value="">{{ __('All Classes') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted text-uppercase mb-1">{{ __('Filter by Section') }}</label>
                            <select id="filter_section" class="form-control select2">
                                <option value="">{{ __('All Sections') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted text-uppercase mb-1">{{ __('Select Exam') }} <span class="text-danger">*</span></label>
                            <select id="filter_exam" class="form-control select2">
                                <option value="">{{ __('Select Exam...') }}</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" data-school="{{ $exam->school_id }}">{{ $exam->name }} [{{ $exam->session ?? 'N/A' }}]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-primary shadow-sm btn-block" id="resetFilter">
                            <i class="fas fa-sync-alt mr-1"></i> {{ __('Reset Filters') }}
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-borderless" id="studentsTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="rounded-left">{{ __('Roll #') }}</th>
                                <th>{{ __('Student Name') }}</th>
                                <th>{{ __('School') }}</th>
                                <th>{{ __('Class & Section') }}</th>
                                <th width="150" class="text-center rounded-right">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admit Card Modal -->
<div class="modal fade" id="admitCardModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title"><i class="fas fa-print mr-2"></i>{{ __('Student Admit Card') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" id="printArea">
                <div class="admit-card-container p-4">
                    <div class="card border-primary" style="border: 2px solid #007bff !important;">
                        <div class="card-header bg-white border-0 text-center pb-0">
                            <div class="row align-items-center">
                                <div class="col-2 text-left">
                                    <img id="schoolLogo" src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="Logo" style="height: 60px;">
                                </div>
                                <div class="col-8">
                                    <h2 class="mb-0 font-weight-bold" id="cardSchoolName"></h2>
                                    <p class="mb-0 text-muted" id="cardSchoolAddress"></p>
                                    <h4 class="mt-2 text-primary font-weight-bold text-uppercase" style="letter-spacing: 2px;">{{ __('Examination Admit Card') }}</h4>
                                    <h5 id="cardExamName" class="font-weight-bold text-dark"></h5>
                                </div>
                                <div class="col-2 text-right">
                                 <div id="studentPhotoContainer" style="width: 80px; height: 100px; border: 1px solid #ddd; padding: 2px;">
                                     <img id="cardStudentPhoto" src="" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('vendor/adminlte/dist/img/avatar5.png') }}';">
                                 </div>
                                </div>
                            </div>
                            <hr class="border-primary my-2" style="border-top: 2px solid #007bff !important;">
                        </div>
                        <div class="card-body py-2">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="mb-2"><strong>{{ __('Student Name') }}:</strong> <span id="cardStudentName" class="text-uppercase ml-1"></span></div>
                                    <div class="mb-2"><strong>{{ __('Father\'s Name') }}:</strong> <span id="cardFatherName" class="ml-1"></span></div>
                                    <div class="mb-2"><strong>{{ __('Roll Number') }}:</strong> <span id="cardRollNumber" class="badge badge-primary py-1 px-3 ml-1"></span></div>
                                </div>
                                <div class="col-6 text-right">
                                    <div class="mb-2"><strong>{{ __('Class') }}:</strong> <span id="cardGrade" class="ml-1"></span></div>
                                    <div class="mb-2"><strong>{{ __('Section') }}:</strong> <span id="cardSection" class="ml-1"></span></div>
                                    <div class="mb-2"><strong>{{ __('DOB') }}:</strong> <span id="cardDOB" class="ml-1"></span></div>
                                </div>
                            </div>
                            
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="bg-light">
                                    <tr class="text-center">
                                        <th width="50">#</th>
                                        <th>{{ __('Subject') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Room') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="cardTimetable">
                                </tbody>
                            </table>

                            <div class="mt-3 p-2 bg-light rounded small">
                                <strong>{{ __('Instructions:') }}</strong>
                                <ul class="mb-0 pl-3">
                                    <li>{{ __('Please bring this admit card to the examination hall.') }}</li>
                                    <li>{{ __('Candidates should be present 15 minutes before the exam starts.') }}</li>
                                    <li>{{ __('No electronic gadgets like phones or calculators are allowed.') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 py-4 mt-2">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <div style="border-top: 1px dashed #000; padding-top: 5px;">{{ __('Class Teacher') }}</div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4 text-center">
                                    <div style="border-top: 1px dashed #000; padding-top: 5px;">{{ __('Principal Signature') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary px-4 shadow" onclick="printAdmitCard()"><i class="fas fa-print mr-2"></i>{{ __('Print Admit Card') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
    .admit-card-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        #printArea, #printArea * {
            visibility: visible;
        }
        #printArea {
            position: fixed;
            left: 0;
            top: 0;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .modal-content {
            border: none !important;
            box-shadow: none !important;
        }
        .modal-header, .modal-footer, .btn, .no-print {
            display: none !important;
        }
        .card {
            border: 2px solid #000 !important;
        }
        .text-primary {
            color: #000 !important;
        }
        .badge-primary {
            background-color: transparent !important;
            color: #000 !important;
            border: 1px solid #000 !important;
        }
        .admit-card-container {
            padding: 10px !important;
        }
    }

    #studentsTable thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .select2-container--bootstrap4 .select2-selection {
        border-radius: 0.25rem;
        height: calc(2.25rem + 2px);
    }
</style>
@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $('.select2').select2({ theme: 'bootstrap4' });

        let table = $('#studentsTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('admit-cards.index') }}",
                data: function(d) {
                    d.school_id = $('#filter_school').val();
                    d.grade_id = $('#filter_grade').val();
                    d.section_id = $('#filter_section').val();
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
                    className: 'font-weight-bold text-dark'
                },
                { 
                    data: 'school',
                    render: function(data) {
                        return data ? `<span class="badge badge-light border text-muted px-2">${data.name}</span>` : '-';
                    }
                },
                { 
                    data: 'grade',
                    render: function(data, type, row) {
                        let gradeName = data ? data.name : 'N/A';
                        let sectionName = row.section ? row.section.name : 'N/A';
                        return `<div class="font-weight-bold text-primary">${gradeName}</div><div class="small text-muted">${sectionName}</div>`;
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    render: function(data) {
                        return `<button class="btn btn-primary btn-sm rounded-pill px-3 viewAdmitCard" data-id="${data}">
                                    <i class="fas fa-eye mr-1"></i> {{ __('View Card') }}
                                </button>`;
                    },
                    orderable: false
                }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "{{ __('Search students...') }}",
                lengthMenu: "_MENU_ entries per page",
            },
            "drawCallback": function( settings ) {
                $('.dataTables_filter input').addClass('form-control shadow-sm border-0 bg-light');
            }
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
                confirmButtonColor: '#3085d6'
            });
        }

        // Enforcement: Disable dependent fields initially
        $('#filter_grade, #filter_section, #filter_exam').prop('disabled', true);

        const rawGrades = @json($grades);
        const rawSections = @json($sections);

        function populateGrades(schoolId) {
            let gradeSelect = $('#filter_grade');
            gradeSelect.empty().append('<option value="">{{ __("All Classes") }}</option>');
            
            let filteredGrades = rawGrades;
            if (schoolId) {
                filteredGrades = rawGrades.filter(g => String(g.school_id) === String(schoolId));
            }
            
            // Re-group by school for better UI if not filtering by a single school
            if (!schoolId) {
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

        function populateSections(gradeId, schoolId) {
            let sectionSelect = $('#filter_section');
            sectionSelect.empty().append('<option value="">{{ __("All Sections") }}</option>');
            
            let filteredSections = rawSections;
            if (gradeId) {
                filteredSections = rawSections.filter(s => String(s.grade_id) === String(gradeId));
            } else if (schoolId) {
                filteredSections = rawSections.filter(s => String(s.school_id) === String(schoolId));
            }
            
            if (filteredSections.length === 0 && gradeId) {
                sectionSelect.find('option[value=""]').text('{{ __("No Sections Available") }}');
            } else {
                sectionSelect.find('option[value=""]').text('{{ __("All Sections") }}');
            }

            // Group by class if showing for a whole school/all schools
            if (!gradeId) {
                let classNames = [...new Set(filteredSections.map(s => s.grade ? s.grade.name : 'Unknown Class'))];
                classNames.sort().forEach(className => {
                    let group = $('<optgroup>').attr('label', className);
                    filteredSections.filter(s => (s.grade ? s.grade.name : 'Unknown Class') === className).forEach(section => {
                        group.append(new Option(section.name, section.id));
                    });
                    sectionSelect.append(group);
                });
            } else {
                filteredSections.forEach(section => {
                    sectionSelect.append(new Option(section.name, section.id));
                });
            }
            
            sectionSelect.trigger('change.select2');
        }

        // Initialize lists
        populateGrades($('#filter_school').val());
        populateSections($('#filter_grade').val(), $('#filter_school').val());

        $('#filter_school').on('change', function() {
            let schoolId = $(this).val();
            let gradeSelect = $('#filter_grade');
            let sectionSelect = $('#filter_section');
            let examSelect = $('#filter_exam');
            
            if(schoolId) {
                gradeSelect.prop('disabled', false);
                populateGrades(schoolId);

                // Filter Exams
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
                sectionSelect.prop('disabled', true).val('').trigger('change');
                examSelect.prop('disabled', true).val('').trigger('change');
                populateGrades('');
            }
            
            gradeSelect.val('').trigger('change');
            reloadTable();
        });

        $('#filter_grade').on('change', function() {
            let gradeId = $(this).val();
            let schoolId = $('#filter_school').val();
            let sectionSelect = $('#filter_section');
            
            if(gradeId) {
                sectionSelect.prop('disabled', false);
                populateSections(gradeId, schoolId);
                $('#filter_exam').prop('disabled', false);
            } else {
                sectionSelect.prop('disabled', true).val('').trigger('change');
                if(!schoolId) $('#filter_exam').prop('disabled', true).val('').trigger('change');
                populateSections('', schoolId);
            }
            
            sectionSelect.trigger('change.select2');
            reloadTable();
        });

        // Sequence Enforcement Alerts
        $('#filter_grade').on('select2:opening', function(e) {
            if (!$('#filter_school').val()) {
                e.preventDefault();
                showSequenceError('{{ __("School") }}');
            }
        });

        $('#filter_section').on('select2:opening', function(e) {
            if (!$('#filter_grade').val()) {
                e.preventDefault();
                showSequenceError('{{ __("Class") }}');
            }
        });

        $('#filter_exam').on('select2:opening', function(e) {
            if (!$('#filter_school').val()) {
                e.preventDefault();
                showSequenceError('{{ __("School") }}');
            }
        });

        $('#filter_section').on('change', reloadTable);

        $('#resetFilter').click(function() {
            $('#filter_school').val('').trigger('change');
            $('#filter_grade').val('').trigger('change');
            $('#filter_section').val('').trigger('change');
            $('#filter_exam').val('').trigger('change');
        });

        $('body').on('click', '.viewAdmitCard', function() {
            let id = $(this).data('id');
            let examId = $('#filter_exam').val();
            
            if(!examId) {
                Swal.fire({
                    title: '{{ __("Exam Required") }}',
                    text: '{{ __("Please select an Exam from the filter above to generate an admit card.") }}',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            Swal.showLoading();
            
            $.get(`/admin/admit-cards/${id}`, { exam_id: examId }, function(data) {
                Swal.close();
                
                // Populate Card
                $('#cardSchoolName').text(data.student.school ? data.student.school.name : 'SMART SCHOOL');
                $('#cardSchoolAddress').text(data.student.school ? data.student.school.address : '');
                $('#cardExamName').text(data.exam.name + (data.exam.session ? ' [' + data.exam.session + ']' : ''));
                $('#cardStudentName').text(data.student.name);
                $('#cardFatherName').text(data.student.father_name || '-');
                $('#cardRollNumber').text(data.student.roll_number || '-');
                $('#cardGrade').text(data.student.grade ? data.student.grade.name : 'N/A');
                $('#cardSection').text(data.student.section ? data.student.section.name : 'N/A');
                $('#cardDOB').text(data.student.dob || '-');
                
                // Photo
                let photoUrl = data.student.photo_url;
                if(photoUrl && photoUrl.trim() !== "") {
                    $('#cardStudentPhoto').attr('src', photoUrl);
                } else {
                    $('#cardStudentPhoto').attr('src', '{{ asset("vendor/adminlte/dist/img/avatar5.png") }}');
                }

                // Timetable
                let timetableRows = '';
                if(data.timetable.length === 0) {
                    timetableRows = '<tr><td colspan="5" class="text-center text-muted py-3">{{ __("No timetable generated for this section.") }}</td></tr>';
                } else {
                    data.timetable.forEach((item, index) => {
                        timetableRows += `
                            <tr class="text-center">
                                <td>${index + 1}</td>
                                <td class="text-left font-weight-bold">${item.subject ? item.subject.name : 'N/A'}</td>
                                <td>${item.exam_date}</td>
                                <td>${item.start_time} - ${item.end_time}</td>
                                <td>${item.room_number || 'TBD'}</td>
                            </tr>
                        `;
                    });
                }
                $('#cardTimetable').html(timetableRows);
                
                $('#admitCardModal').modal('show');
            }).fail(function() {
                Swal.fire('{{ __("Error") }}', '{{ __("Failed to load admit card data.") }}', 'error');
            });
        });
    });

    function printAdmitCard() {
        window.print();
    }
</script>
@stop
