@extends('adminlte::page')

@section('title', __('Print Student List'))

@section('content_header')
    <h1>{{ __('Print Student List') }}</h1>
@stop

@section('css')
<style>
    .select2-container--disabled {
        cursor: not-allowed;
        pointer-events: auto !important; /* Ensure click events are captured */
    }
    .select2-container--disabled .select2-selection {
        background-color: #f4f6f9 !important;
        opacity: 0.6;
    }
</style>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-print mr-2"></i>{{ __('Select Details') }}
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('students.print') }}" method="GET" target="_blank">
                        
                        <div class="form-group mb-4" id="wrapper_school">
                            <label class="font-weight-bold">{{ __('Select School') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                                <option value="">{{ __('Choose School...') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4" id="wrapper_exam_type">
                            <label class="font-weight-bold">{{ __('Select Exam Type') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="exam_type_id" id="exam_type_id" required style="width: 100%;" disabled>
                                <option value="">{{ __('Choose Exam Type...') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-4" id="wrapper_teacher">
                            <label class="font-weight-bold">{{ __('Select Class Teacher') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="teacher_id" id="teacher_id" required style="width: 100%;" disabled>
                                <option value="">{{ __('Choose Teacher...') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-4" id="wrapper_grade">
                            <label class="font-weight-bold">{{ __('Select Class') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="grade_id" id="grade_id" required style="width: 100%;" disabled>
                                <option value="">{{ __('Choose Class...') }}</option>
                            </select>
                        </div>
                        
                        <div class="form-group mb-4" id="wrapper_section">
                            <label class="font-weight-bold">{{ __('Select Class Section (Optional)') }}</label>
                            <select class="form-control select2" name="section_id" id="section_id" style="width: 100%;" disabled>
                                <option value="">{{ __('All Sections...') }}</option>
                            </select>
                        </div>
                        
                        <div class="form-group mb-4" id="wrapper_subject">
                            <label class="font-weight-bold">{{ __('Select Subject') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="subject_id" id="subject_id" required style="width: 100%;" disabled>
                                <option value="">{{ __('Choose Subject...') }}</option>
                            </select>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="fas fa-file-alt mr-2"></i> {{ __('Generate & Print List') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        function resetSelect(selector, placeholder = "Choose...") {
            $(selector).html('<option value="">' + placeholder + '</option>').val('').trigger('change');
            $(selector).prop('disabled', true);
        }
        
        function enableSelect(selector) {
            $(selector).prop('disabled', false);
        }

        function showWarning(message) {
            Swal.fire({
                icon: 'warning',
                title: "{{ __('Action Required') }}",
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }

        // Click handlers for disabled fields
        
        $('#wrapper_exam_type').on('click', '.select2-container--disabled', function() {
            showWarning("{{ __('Please select a School first.') }}");
        });

        $('#wrapper_teacher').on('click', '.select2-container--disabled', function() {
            if (!$('#school_id').val()) {
                showWarning("{{ __('Please select a School first.') }}");
            } else {
                showWarning("{{ __('Please select an Exam Type first.') }}");
            }
        });

        $('#wrapper_grade').on('click', '.select2-container--disabled', function() {
            if (!$('#teacher_id').val()) {
                showWarning("{{ __('Please select a Class Teacher first.') }}");
            }
        });

        $('#wrapper_section').on('click', '.select2-container--disabled', function() {
            if (!$('#grade_id').val()) {
                showWarning("{{ __('Please select a Class first.') }}");
            }
        });

        $('#wrapper_subject').on('click', '.select2-container--disabled', function() {
            if (!$('#grade_id').val()) { // Changed dependency to Grade instead of Section
                showWarning("{{ __('Please select a Class first.') }}");
            }
        });

        // 1. School -> Exam Type
        $('#school_id').change(function() {
            let schoolId = $(this).val();
            
            resetSelect('#exam_type_id', "{{ __('Choose Exam Type...') }}");
            resetSelect('#teacher_id', "{{ __('Choose Teacher...') }}");
            resetSelect('#grade_id', "{{ __('Choose Class...') }}");
            resetSelect('#section_id', "{{ __('All Sections...') }}");
            resetSelect('#subject_id', "{{ __('Choose Subject...') }}");

            if (schoolId) {
                $.get("{{ route('exam-types.index') }}", { school_id: schoolId }, function(data) {
                    let options = '<option value="">{{ __('Choose Exam Type...') }}</option>';
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                    }
                    $('#exam_type_id').html(options);
                    enableSelect('#exam_type_id');
                });
            }
        });

        // 2. Exam Type -> Teacher
        $('#exam_type_id').change(function() {
            let examTypeId = $(this).val();
            let schoolId = $('#school_id').val();

            resetSelect('#teacher_id', "{{ __('Choose Teacher...') }}");
            resetSelect('#grade_id', "{{ __('Choose Class...') }}");
            resetSelect('#section_id', "{{ __('All Sections...') }}");
            resetSelect('#subject_id', "{{ __('Choose Subject...') }}");

            if (examTypeId && schoolId) {
                $.get("{{ route('teacher-profiles.index') }}", { school_id: schoolId }, function(data) {
                    let options = '<option value="">{{ __('Choose Teacher...') }}</option>';
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                    }
                    $('#teacher_id').html(options);
                    enableSelect('#teacher_id');
                });
            }
        });

        // 3. Teacher -> Class
        $('#teacher_id').change(function() {
            let teacherId = $(this).val();
            let schoolId = $('#school_id').val();

            resetSelect('#grade_id', "{{ __('Choose Class...') }}");
            resetSelect('#section_id', "{{ __('All Sections...') }}");
            resetSelect('#subject_id', "{{ __('Choose Subject...') }}");

            if (teacherId && schoolId) {
                $.get("{{ route('grades.index') }}", { teacher_id: teacherId, school_id: schoolId }, function(data) {
                    let options = '<option value="">{{ __('Choose Class...') }}</option>';
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            options += `<option value="${item.id}" data-teacher-id="${item.teacher_id}">${item.name}</option>`;
                        });
                    }
                    $('#grade_id').html(options);
                    enableSelect('#grade_id');
                });
            }
        });

        // 4. Class -> Section AND Subject (Parallel)
        $('#grade_id').change(function() {
            let gradeId = $(this).val();

            resetSelect('#section_id', 'All Sections...');
            resetSelect('#subject_id', 'Choose Subject...');

            if (gradeId) {
                // Fetch Sections
                $.get("{{ route('sections.index') }}", { grade_id: gradeId }, function(data) {
                    let options = '<option value="">{{ __('All Sections...') }}</option>';
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                    }
                    $('#section_id').html(options);
                    enableSelect('#section_id'); 
                });

                // Fetch Subjects immediately (Section is optional)
                $.get("{{ route('subjects.index') }}", { grade_id: gradeId }, function(data) {
                    let options = '<option value="">{{ __('Choose Subject...') }}</option>';
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                    }
                    $('#subject_id').html(options);
                    enableSelect('#subject_id');
                });
            }
        });
        
        // No listener needed for #section_id change anymore unless subject depends on it.
    });
</script>
@stop
