@extends('adminlte::page')

@section('title', __('Exam Sheet Plan'))

@section('content_header')
    <h1>{{ __('Exam Sheet Plan') }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-clipboard-list mr-2"></i>{{ __('Create Seating Plan') }}
                    </h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('exam-sheet-plan.generate') }}" method="POST" target="_blank">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">{{ __('Select School') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                                <option value="">{{ __('Choose School...') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                            @error('school_id') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">{{ __('Room Number / Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="room_no" class="form-control" placeholder="{{ __('e.g. Room 101') }}" required>
                            @error('room_no') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <!-- Class 1 Row -->
                            <!-- Class 1 Row -->
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold">{{ __('Class 1 Allocation') }} <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control select2 grade-select" name="grade_ids[0]" id="grade_1" data-index="1" required style="width: 100%;" disabled>
                                            <option value="">{{ __('Choose Class...') }}</option>
                                        </select>
                                        @error('grade_ids.0') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control select2 section-select" name="section_ids[0]" id="section_1" data-index="1" style="width: 100%;" disabled>
                                            <option value="">{{ __('All Sections...') }}</option>
                                        </select>
                                        @error('section_ids.0') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="start_roll_numbers[0]" id="start_roll_1" class="form-control" placeholder="{{ __('Start Roll No (Default: 1)') }}" min="1">
                                        @error('start_roll_numbers.0') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Class 2 Row -->
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold">{{ __('Class 2 Allocation (Optional)') }}</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control select2 grade-select" name="grade_ids[1]" id="grade_2" data-index="2" style="width: 100%;" disabled>
                                            <option value="">{{ __('Choose Class...') }}</option>
                                        </select>
                                        @error('grade_ids.1') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control select2 section-select" name="section_ids[1]" id="section_2" data-index="2" style="width: 100%;" disabled>
                                            <option value="">{{ __('All Sections...') }}</option>
                                        </select>
                                        @error('section_ids.1') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="start_roll_numbers[1]" id="start_roll_2" class="form-control" placeholder="{{ __('Start Roll No (Default: 1)') }}" min="1">
                                        @error('start_roll_numbers.1') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Class 3 Row -->
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold">{{ __('Class 3 Allocation (Optional)') }}</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control select2 grade-select" name="grade_ids[2]" id="grade_3" data-index="3" style="width: 100%;" disabled>
                                            <option value="">{{ __('Choose Class...') }}</option>
                                        </select>
                                        @error('grade_ids.2') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control select2 section-select" name="section_ids[2]" id="section_3" data-index="3" style="width: 100%;" disabled>
                                            <option value="">{{ __('All Sections...') }}</option>
                                        </select>
                                        @error('section_ids.2') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="start_roll_numbers[2]" id="start_roll_3" class="form-control" placeholder="{{ __('Start Roll No (Default: 1)') }}" min="1">
                                        @error('start_roll_numbers.2') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="fas fa-magic mr-2"></i> {{ __('Generate Plan') }}
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
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        $('#school_id').change(function() {
            let schoolId = $(this).val();
            let $gradeSelects = $('.grade-select');
            let $sectionSelects = $('.section-select');

            // Reset Grades
            $gradeSelects.html('<option value="">' + "{{ __('Choose Class...') }}" + '</option>').val('').trigger('change');
            $gradeSelects.prop('disabled', true);
            
            // Reset Sections
            $sectionSelects.html('<option value="">' + "{{ __('All Sections...') }}" + '</option>').val('').trigger('change');
            $sectionSelects.prop('disabled', true);

            if (schoolId) {
                $.get("{{ route('grades.index') }}", { school_id: schoolId }, function(data) {
                    let options = '<option value="">' + "{{ __('Choose Class...') }}" + '</option>';
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                    }
                    $gradeSelects.html(options);
                    $gradeSelects.prop('disabled', false);
                });
            }
        });

        // Grade Change Listener for Sections
        $('.grade-select').change(function() {
            let gradeId = $(this).val();
            let index = $(this).data('index');
            let $sectionSelect = $('#section_' + index);

            $sectionSelect.html('<option value="">' + "{{ __('All Sections...') }}" + '</option>').val('').trigger('change');
            $sectionSelect.prop('disabled', true);

            if (gradeId) {
                // Fetch Sections for this Grade
                $.get("{{ route('sections.index') }}", { grade_id: gradeId }, function(data) {
                    let options = '<option value="">' + "{{ __('All Sections...') }}" + '</option>';
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                    }
                    $sectionSelect.html(options);
                    $sectionSelect.prop('disabled', false);
                });
            }
        });
    });
</script>
@stop
