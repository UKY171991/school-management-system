@extends('adminlte::page')

@section('title', __('Student Admission'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ __('Student Admission & Registration') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Admissions') }}</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Compact Filter Bar -->
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body p-3">
            <form id="filterForm" class="row align-items-end g-2 flex-nowrap overflow-auto py-2">
                @if(auth()->user()->isMasterAdmin())
                <div class="col-auto">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-user-tie mr-1"></i> {{ __('Admin / School') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_school_id" name="school_id" style="min-width: 150px;">
                        <option value="">{{ __('All Admins') }}</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-auto">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-graduation-cap mr-1"></i> {{ __('Class') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_grade_id" name="grade_id" style="min-width: 120px;">
                        <option value="">{{ __('All Classes') }}</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" data-school="{{ $grade->school_id }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-layer-group mr-1"></i> {{ __('Section') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_section_id" name="section_id" style="min-width: 120px;">
                        <option value="">{{ __('All Sections') }}</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-grade="{{ $section->grade_id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn btn-primary shadow-sm px-3">
                        <i class="fas fa-search me-1"></i> {{ __('Filter') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary shadow-sm px-2" id="resetFilter">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
                <div class="col-auto ms-auto d-flex align-items-center gap-2">
                    <a href="{{ route('admissions.bulk') }}" class="btn btn-outline-success shadow-sm px-3 text-nowrap">
                        <i class="fas fa-file-import me-1"></i> {{ __('Bulk Admission') }}
                    </a>
                    <a href="/admin/admissions/print/blank" target="_blank" class="btn btn-outline-primary shadow-sm px-3 text-nowrap">
                        <i class="fas fa-print me-1"></i> {{ __('Print Blank Form') }}
                    </a>
                    <button type="button" class="btn btn-success shadow-sm px-3 text-nowrap" id="createNewStudent">
                        <i class="fas fa-user-plus me-1"></i> {{ __('New Admission') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-list mr-2"></i>{{ __('Registered Students') }}
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="studentsTable" style="width:100%">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-top-0">{{ __('ID') }}</th>
                            <th class="border-top-0">{{ __('Photo') }}</th>
                            <th class="border-top-0">{{ __('Roll #') }}</th>
                            <th class="border-top-0">{{ __('Full Name') }}</th>
                            <th class="border-top-0">{{ __('Father Name') }}</th>
                            <th class="border-top-0">{{ __('Mother Name') }}</th>
                            <th class="border-top-0">{{ __('School') }}</th>
                            <th class="border-top-0">{{ __('Class / Section') }}</th>
                            <th class="border-top-0 text-center" width="120">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="studentList">
                        <!-- Loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Admission Modal -->
<div class="modal fade" id="admissionModal" tabindex="-1" role="dialog" aria-labelledby="admissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form id="admissionForm" name="admissionForm">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="admissionModalLabel">
                        <i class="fas fa-user-graduate mr-2"></i>{{ __('Student Information') }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="student_id" id="student_id">
                    <div class="row">
                        @if(auth()->user()->isMasterAdmin())
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Select Admin Name') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="school_id" name="school_id" required style="width: 100%;">
                                    <option value="">{{ __('Choose Admin...') }}</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="school_id" id="school_id" value="{{ auth()->user()->school_id }}">
                        @endif
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Assigned Class') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="grade_id" name="grade_id" required style="width: 100%;">
                                    <option value="">{{ __('Choose Class...') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Section') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="section_id" name="section_id" required style="width: 100%;">
                                    <option value="">{{ __('Choose Section...') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-user text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="name" name="name" required placeholder="{{ __('Enter full name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Gender') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="gender" name="gender" required style="width: 100%;">
                                    <option value="">{{ __('Select Gender...') }}</option>
                                    <option value="Male">{{ __('Male') }}</option>
                                    <option value="Female">{{ __('Female') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope text-muted"></i></span>
                                    </div>
                                    <input type="email" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="email" name="email" required placeholder="{{ __('john@example.com') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Roll Number') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-hashtag text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="roll_number" name="roll_number" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Date of Birth') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="form_dob" name="dob" required placeholder="{{ __('YYYY-MM-DD') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Admission Date') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-calendar-check text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="form_admission_date" name="admission_date" required placeholder="{{ __('YYYY-MM-DD') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Father Name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-user-tie text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="father_name" name="father_name" placeholder="{{ __('Enter father name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Father Phone Number') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-phone text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="father_phone" name="father_phone" placeholder="{{ __('Enter father phone number') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Mother Name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-user text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="mother_name" name="mother_name" placeholder="{{ __('Enter mother name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Mother Phone Number') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-phone text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="mother_phone" name="mother_phone" placeholder="{{ __('Enter mother phone number') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Caste') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-users text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="caste" name="caste" placeholder="{{ __('Enter caste') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Previous School Name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-school text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="previous_school" name="previous_school" placeholder="{{ __('Enter previous school name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Adhaar Number') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-id-card text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="adhaar_number" name="adhaar_number" placeholder="{{ __('Enter Adhaar number') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Apaar ID') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-id-badge text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0 pl-0 mt-0 h-auto py-2" id="apaar_id" name="apaar_id" placeholder="{{ __('Enter Apaar ID') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">{{ __('Student Photo') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="photo" name="photo">
                                    <label class="custom-file-label" for="photo">{{ __('Choose file') }}</label>
                                </div>
                                <div id="photoContainer" class="mt-3" style="display:none;">
                                    <div class="position-relative d-inline-block">
                                        <img id="previewImg" src="" class="img-thumbnail" width="100" style="border-radius: 10px;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute shadow-sm" style="top: -8px; right: -8px; border-radius: 50%; width: 25px; height: 25px; padding: 0; line-height: 25px;" id="removePhotoBtn" title="{{ __('Remove Photo') }}">
                                            <i class="fas fa-times" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 py-3">
                    <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="saveBtn">{{ __('Save Record') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0 !important;
        margin: 0 !important;
    }
    #studentsTable thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #4a5568;
    }
    .input-group-text {
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }
    .form-control {
        border-color: #e2e8f0;
    }
    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        border-color: #63b3ed;
    }
    .custom-file-input:lang(en)~.custom-file-label::after {
        content: "{{ __('Browse') }}";
    }
</style>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
    // Initialize Flatpickr manually on Date of Birth so we can control it via API
    var dobPicker = flatpickr('#form_dob', {
        dateFormat: 'Y-m-d',
        allowInput: true,
        maxDate: 'today'
    });

    var admissionDatePicker = flatpickr('#form_admission_date', {
        dateFormat: 'Y-m-d',
        allowInput: true,
        defaultDate: 'today'
    });

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Custom File Input
        bsCustomFileInput.init();

        // Initialize DataTable
        let table = $('#studentsTable').DataTable({
            processing: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "{{ __('Search records...') }}"
            },
            ajax: {
                url: "{{ route('admissions.index') }}",
                data: function (d) {
                    d.grade_id = $('#filter_grade_id').val();
                    d.section_id = $('#filter_section_id').val();
                    d.school_id = $('#filter_school_id').val();
                },
                dataSrc: ""
            },
            columns: [
                {data: 'id', name: 'id'},
                {
                    data: 'photo',
                    name: 'photo',
                    render: function(data, type, row) {
                        return row.photo_url ? `<img src="${row.photo_url}" class="rounded-circle shadow-sm" width="40" height="40" alt="{{ __('Student Photo') }}">` : `<div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fas fa-user"></i></div>`;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'roll_number', 
                    render: function(data) {
                        return `<span class="badge badge-pill badge-info px-3 py-2 shadow-sm font-weight-normal">${data}</span>`;
                    }
                },
                {
                    data: 'name',
                    render: function(data) {
                        return `<div class="font-weight-bold text-dark">${data}</div>`;
                    }
                },
                {
                    data: 'father_name',
                    render: function(data) {
                        return `<div class="text-muted">${data || "{{ __('N/A') }}"}</div>`;
                    }
                },
                {
                    data: 'mother_name',
                    render: function(data) {
                        return `<div class="text-muted">${data || "{{ __('N/A') }}"}</div>`;
                    }
                },
                {
                    data: 'school', 
                    name: 'school',
                    render: function(data) {
                        return data ? `<span class="badge badge-light border text-secondary font-weight-500"><i class="fas fa-school mr-1 small"></i>${data.name}</span>` : "{{ __('N/A') }}";
                    }
                },
                {
                    data: 'grade', 
                    name: 'grade',
                    render: function(data, type, row) {
                        let sectionName = row.section ? row.section.name : "{{ __('N/A') }}";
                        return data ? `<span class="text-primary font-weight-500"><i class="fas fa-chalkboard-teacher mr-1 small"></i>${data.name} - ${sectionName}</span>` : "{{ __('N/A') }}";
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    render: function (data, type, row) {
                        return `
                            <div class="btn-group">
                                <button class="btn btn-outline-info btn-sm border-0 viewStudent" data-id="${data}" title="{{ __('View Details') }}">
                                    <i class="fas fa-eye"></i> {{ __('View') }}
                                </button>
                                <button class="btn btn-outline-warning btn-sm border-0 editStudent" data-id="${data}" title="{{ __('Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm border-0 deleteStudent" data-id="${data}" title="{{ __('Delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            rowId: function(a) { return 'student_' + a.id; },
            dom: '<"d-flex justify-content-between mb-3"lf>rt<"d-flex justify-content-between mt-3"ip>'
        });

        // Student photo preview
        $('#photo').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImg').attr('src', e.target.result);
                    $('#photoContainer').show();
                    $('#remove_photo').val(0); // New file selected, so don't remove
                }
                reader.readAsDataURL(file);
            }
        });

        $(document).on('click', '#removePhotoBtn', function(e) {
            e.preventDefault();
            let studentId = $('#student_id').val();
            
            if (studentId) {
                if (confirm("{{ __('Are you sure you want to delete this photo permanently?') }}")) {
                    $.ajax({
                        url: `/admin/admissions/${studentId}/delete-photo`,
                        type: 'DELETE',
                        success: function(data) {
                            $('#photo').val('');
                            $('.custom-file-label').html("{{ __('Choose file') }}");
                            $('#previewImg').attr('src', '');
                            $('#photoContainer').hide();
                            table.ajax.reload();
                            Swal.fire("{{ __('Deleted!') }}", data.success, 'success');
                        },
                        error: function() {
                            Swal.fire("{{ __('Error') }}", "{{ __('Failed to delete photo') }}", 'error');
                        }
                    });
                }
            } else {
                // Just clear preview for new records
                $('#photo').val('');
                $('.custom-file-label').html("{{ __('Choose file') }}");
                $('#previewImg').attr('src', '');
                $('#photoContainer').hide();
            }
        });

        function resetAdmissionModal() {
            $('#admissionForm').trigger("reset");
            $('#student_id').val('');
            // Clear Flatpickr date properly
            if (dobPicker) dobPicker.clear();
            @if(auth()->user()->isMasterAdmin())
                $('#school_id').val('').trigger('change.select2');
            @endif
            $('#grade_id').html('<option value="">{{ __("Choose Class...") }}</option>');
            $('#section_id').html('<option value="">{{ __("Choose Section...") }}</option>');
            $('#gender').val('').trigger('change.select2');
            $('#caste').val('');
            $('#father_phone').val('');
            $('#mother_phone').val('');
            if (admissionDatePicker) admissionDatePicker.setDate(new Date(), true);
            $('#previous_school').val('');
            $('#adhaar_number').val('');
            $('#apaar_id').val('');
            $('#photo').val('');
            $('.custom-file-label').html("{{ __('Choose file') }}");
            $('#previewImg').attr('src', '');
            $('#photoContainer').hide();
            $('#admissionModalLabel').html('<i class="fas fa-user-plus mr-2"></i>{{ __("New Student Admission") }}');
        }

        $('#filterForm').submit(function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        $('#resetFilter').click(function() {
            $('#filterForm').trigger("reset");
            $('.select2').val('').trigger('change.select2');
            table.ajax.reload();
        });

        $('#createNewStudent').click(function () {
            resetAdmissionModal();
            
            $.get("{{ route('admissions.index') }}", { next_roll: 1 }, function(data) {
                $('#roll_number').val(data.next_roll);
            });

            @if(!auth()->user()->isMasterAdmin())
            // For non-master admin, automatically load grades for their school
            let schoolId = $('#school_id').val();
            if(schoolId) {
                $.get("{{ route('grades.index') }}", { school_id: schoolId }, function(data) {
                    let options = '<option value="">{{ __('Choose Class...') }}</option>';
                    data.forEach(grade => {
                        options += `<option value="${grade.id}">${grade.name}</option>`;
                    });
                    $('#grade_id').html(options);
                });
            }
            @endif

            $('#admissionModal').modal('show');
        });

        $('#admissionForm').submit(function (e) {
            e.preventDefault();
            let id = $('#student_id').val();
            // Use POST for both creating and updating (using _method for put) when uploading files
            let url = id ? `/admin/admissions/${id}` : "{{ route('admissions.store') }}";
            
            let formData = new FormData(this);
            if (id) {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                data: formData,
                url: url,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#admissionModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        title: "{{ __('Success!') }}",
                        text: data.success,
                        type: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                },
                error: function (data) {
                    let errors = data.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value + '<br>';
                    });
                    Swal.fire("{{ __('Validation Error') }}", errorMsg, 'error');
                }
            });
        });

        $('body').on('click', '.editStudent', function () {
            let id = $(this).data('id');
            resetAdmissionModal(); // Clear before loading
            
            $.get(`/admin/admissions/${id}`, function (data) {
                $('#admissionModalLabel').html('<i class="fas fa-edit mr-2"></i>{{ __('Edit Student Profile') }}');
                $('#student_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#roll_number').val(data.roll_number);
                
                // Set DOB via Flatpickr API so the calendar display updates correctly
                if (data.dob) {
                    let dob = data.dob;
                    // Normalize slashes to dashes
                    dob = dob.replace(/\//g, '-');
                    // Handle DD-MM-YYYY format -> convert to YYYY-MM-DD
                    if (/^\d{2}-\d{2}-\d{4}/.test(dob)) {
                        let parts = dob.trim().split(/[- ]/);
                        dob = `${parts[2]}-${parts[1]}-${parts[0]}`;
                    } else if (dob.length > 10) {
                        dob = dob.substring(0, 10);
                    }
                    // Use Flatpickr's setDate API to properly show the date in the picker
                    if (dobPicker) {
                        dobPicker.setDate(dob, true);
                    } else {
                        $('#form_dob').val(dob);
                    }
                }
                
                $('#father_name').val(data.father_name || '');
                $('#mother_name').val(data.mother_name || '');
                $('#gender').val(data.gender || '').trigger('change.select2');
                $('#caste').val(data.caste || '');
                $('#father_phone').val(data.father_phone || '');
                $('#mother_phone').val(data.mother_phone || '');
                
                if (data.admission_date) {
                    admissionDatePicker.setDate(data.admission_date, true);
                } else {
                    admissionDatePicker.setDate(new Date(), true);
                }

                $('#previous_school').val(data.previous_school || '');
                $('#adhaar_number').val(data.adhaar_number || '');
                $('#apaar_id').val(data.apaar_id || '');
                
                // Set School (UI only to avoid triggering change scripts that clear things)
                $('#school_id').val(data.school_id).trigger('change.select2');
                
                // Load Class and Section sequentially to ensure correct selection
                if (data.school_id) {
                     $.get("{{ route('grades.index') }}", { school_id: data.school_id }, function(grades) {
                        let options = '<option value="">{{ __('Choose Class...') }}</option>';
                        grades.forEach(grade => {
                            options += `<option value="${grade.id}" ${grade.id == data.grade_id ? 'selected' : ''}>${grade.name}</option>`;
                        });
                        $('#grade_id').html(options).trigger('change.select2');
                        
                        if(data.grade_id) {
                             $.get("{{ route('sections.index') }}", { grade_id: data.grade_id }, function(sections) {
                                let options = '<option value="">{{ __('Choose Section...') }}</option>';
                                sections.forEach(sec => {
                                    options += `<option value="${sec.id}" ${sec.id == data.section_id ? 'selected' : ''}>${sec.name}</option>`;
                                });
                                $('#section_id').html(options).trigger('change.select2');
                            });
                        }
                    });
                }

                if(data.photo_url) {
                    $('#previewImg').attr('src', data.photo_url);
                    $('#photoContainer').show();
                }
                
                $('#admissionModal').modal('show');
            });
        });

        $('body').on('click', '.deleteStudent', function () {
            let id = $(this).data('id');
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('Student records and attendance data will be lost.') }}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: "{{ __('Confirm Delete') }}"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: `/admin/admissions/${id}`,
                        success: function (data) {
                            table.ajax.reload();
                            Swal.fire("{{ __('Deleted!') }}", data.success, 'success');
                        }
                    });
                }
            });
        });

        $('body').on('click', '.viewStudent', function () {
            let id = $(this).data('id');
            $.get(`/admin/admissions/${id}`, function (data) {
                // Create a detailed view modal
                let modalHtml = `
                    <div class="modal fade" id="viewStudentModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-user-graduate mr-2"></i>{{ __('Student Details') }}
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center">
                                            ${data.photo_url ? `<img src="${data.photo_url}" class="rounded-circle shadow-lg mb-3" width="120" height="120" alt="{{ __('Student Photo') }}">` : '<div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center shadow-lg mb-3" style="width: 120px; height: 120px;"><i class="fas fa-user fa-2x text-white"></i></div>'}
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="text-primary font-weight-bold">${data.name}</h4>
                                            <p class="text-muted mb-1"><i class="fas fa-id-badge mr-1"></i> ${data.roll_number}</p>
                                            <p class="mb-1"><i class="fas fa-envelope mr-1"></i> ${data.email}</p>
                                            <p class="mb-1"><i class="fas fa-calendar mr-1"></i> ${data.dob}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Gender') }}:</strong> ${data.gender || '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Caste') }}:</strong> ${data.caste || '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Admission Date') }}:</strong> ${data.admission_date || '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Father Name') }}:</strong> ${data.father_name || '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Father Phone') }}:</strong> ${data.father_phone || '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Mother Name') }}:</strong> ${data.mother_name || '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Mother Phone') }}:</strong> ${data.mother_phone || '{{ __('N/A') }}'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-school mr-1"></i>{{ __('School') }}:</strong> ${data.school ? data.school.name : '{{ __('N/A') }}'}</p>
                                            <p><strong><i class="fas fa-chalkboard-teacher mr-1"></i>{{ __('Class') }}:</strong> ${data.grade ? data.grade.name : '{{ __('N/A') }}'} - {{ __('Section') }}: ${data.section ? data.section.name : '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Previous School') }}:</strong> ${data.previous_school || '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Adhaar Number') }}:</strong> ${data.adhaar_number || '{{ __('N/A') }}'}</p>
                                            <p><strong>{{ __('Apaar ID') }}:</strong> ${data.apaar_id || '{{ __('N/A') }}'}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="/admin/admissions/print/${data.id}" target="_blank" class="btn btn-primary"><i class="fas fa-print mr-1"></i> {{ __('Print Form') }}</a>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove existing modal if any
                $('#viewStudentModal').remove();
                $('body').append(modalHtml);
                $('#viewStudentModal').modal('show');
                
                // Clean up modal when hidden
                $('#viewStudentModal').on('hidden.bs.modal', function () {
                    $(this).remove();
                });
            });
        });

        // Dependent Dropdowns Logic (Global)
        // 1. School -> Grade (Filter & Modal)
        // 1. School -> Grade (Filter & Modal)
        
        // Modal School Change -> AJAX fetch Grades
        $('#school_id').change(function() {
            let schoolId = $(this).val();
            let $gradeSelect = $('#grade_id');
            let $sectionSelect = $('#section_id');

            // Reset downstream dropdowns
            $gradeSelect.html('<option value="">{{ __('Choose Class...') }}</option>');
            $sectionSelect.html('<option value="">{{ __('Choose Section...') }}</option>');

            if(!schoolId) {
                return;
            }

            $.get("{{ route('grades.index') }}", { school_id: schoolId }, function(data) {
                let options = '<option value="">{{ __('Choose Class...') }}</option>';
                data.forEach(grade => {
                    options += `<option value="${grade.id}">${grade.name}</option>`;
                });
                $gradeSelect.html(options);
            });
        });

        // Filter School Change -> Update Grades
        $('#filter_school_id').change(function() {
            let schoolId = $(this).val();
            let $gradeSelect = $('#filter_grade_id');
            let $sectionSelect = $('#filter_section_id');

            // Reset downstream
            $gradeSelect.html('<option value="">{{ __("All Classes") }}</option>');
            $sectionSelect.html('<option value="">{{ __("All Sections") }}</option>');

            if (!schoolId) {
                return;
            }

            $.get("{{ route('grades.index') }}", { school_id: schoolId }, function(data) {
                let options = '<option value="">{{ __("All Classes") }}</option>';
                data.forEach(grade => {
                    options += `<option value="${grade.id}">${grade.name}</option>`;
                });
                $gradeSelect.html(options).trigger('change.select2');
            });
        });

        // Modal Grade Change -> AJAX fetch Sections
        $('#grade_id').change(function() {
            let gradeId = $(this).val();
            let $sectionSelect = $('#section_id');
            
            if(!gradeId) {
                $sectionSelect.html('<option value="">{{ __("Choose Section...") }}</option>');
                return;
            }

            $.get("{{ route('sections.index') }}", { grade_id: gradeId }, function(data) {
                let options = '<option value="">{{ __("Choose Section...") }}</option>';
                data.forEach(sec => {
                    options += `<option value="${sec.id}">${sec.name}</option>`;
                });
                $sectionSelect.html(options);
            });
        });

        // Filter Grade Change -> Update Sections
        $('#filter_grade_id').change(function() {
            let gradeId = $(this).val();
            let $sectionSelect = $('#filter_section_id');
            
            $sectionSelect.html('<option value="">{{ __("All Sections") }}</option>');

            if (!gradeId) {
                return;
            }

            $.get("{{ route('sections.index') }}", { grade_id: gradeId }, function(data) {
                let options = '<option value="">{{ __("All Sections") }}</option>';
                data.forEach(sec => {
                    options += `<option value="${sec.id}">${sec.name}</option>`;
                });
                $sectionSelect.html(options).trigger('change.select2');
            });
        });
        
    });
</script>
@stop
