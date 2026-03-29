@extends('adminlte::page')

@section('title', __('Bulk Admission'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ __('Bulk Student Admission') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">{{ __('Admissions') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Bulk') }}</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-file-upload mr-2"></i>{{ __('Import Student List (CSV)') }}
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex">
                            <i class="fas fa-info-circle fa-2x mr-3 text-info"></i>
                            <div>
                                <h6 class="font-weight-bold">{{ __('CSV Format Instructions:') }}</h6>
                                <p class="mb-2 small">
                                    {{ __('Column order MUST be exactly: Name, Email, Roll Number, Registration Number, Session Year, DOB, Gender, Admission Date, Father Name, Father Phone, Mother Name, Mother Phone, Address, Caste, Previous School, Adhaar Number, Apaar ID.') }}<br>
                                    {{ __('The first row should be the header row. Date format: YYYY-MM-DD.') }}
                                </p>
                                <a href="{{ route('admissions.download-sample') }}" class="btn btn-info btn-sm shadow-sm">
                                    <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admissions.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @if(auth()->user()->isMasterAdmin())
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold text-muted small text-uppercase mb-2 d-block">{{ __('Select Admin / School') }}</label>
                                <select class="form-control select2" id="school_id" name="school_id" required>
                                    <option value="">{{ __('Choose Admin...') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="col-md-{{ auth()->user()->isMasterAdmin() ? '6' : '12' }} mb-3">
                                <label class="font-weight-bold text-muted small text-uppercase mb-2 d-block">{{ __('Branch / Location') }}</label>
                                <select class="form-control select2" id="branch_id" name="branch_id">
                                    <option value="">{{ __('Select Branch (Optional)') }}</option>
                                    @if(!auth()->user()->isMasterAdmin())
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold text-muted small text-uppercase mb-2 d-block">{{ __('Assign to Class') }}</label>
                                <select class="form-control select2" id="grade_id" name="grade_id" required>
                                    <option value="">{{ __('Choose Class...') }}</option>
                                    @if(!auth()->user()->isMasterAdmin())
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold text-muted small text-uppercase mb-2 d-block">{{ __('Assign to Section') }}</label>
                                <select class="form-control select2" id="section_id" name="section_id" required>
                                    <option value="">{{ __('Choose Section...') }}</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="font-weight-bold text-muted small text-uppercase mb-2 d-block">{{ __('Select CSV File') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="csv_file" name="csv_file" accept=".csv" required>
                                    <label class="custom-file-label" for="csv_file">{{ __('Choose file') }}</label>
                                </div>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm px-5">
                                    <i class="fas fa-upload mr-2"></i> {{ __('Start Import') }}
                                </button>
                                <a href="{{ route('admissions.index') }}" class="btn btn-link text-muted mt-3 d-block">
                                    {{ __('Cancel and Go Back') }}
                                </a>
                            </div>
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
    $(function () {
        $('.select2').select2({ theme: 'bootstrap4' });

        // Update custom file label
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Dependent dropdown logic
        $('#school_id').change(function() {
            let schoolId = $(this).val();
            let $branchSelect = $('#branch_id');
            let $gradeSelect = $('#grade_id');
            let $sectionSelect = $('#section_id');

            $branchSelect.html('<option value="">{{ __("Select Branch (Optional)") }}</option>');
            $gradeSelect.html('<option value="">{{ __("Choose Class...") }}</option>');
            $sectionSelect.html('<option value="">{{ __("Choose Section...") }}</option>');

            if(!schoolId) return;

            // Fetch Branches
            $.get("{{ route('branches.index') }}", { school_id: schoolId }, function(data) {
                let options = '<option value="">{{ __("Select Branch (Optional)") }}</option>';
                data.forEach(branch => {
                    options += `<option value="${branch.id}">${branch.name}</option>`;
                });
                $branchSelect.html(options).trigger('change.select2');
            });

            // Fetch Grades (all for school initially)
            $.get("{{ route('grades.index') }}", { school_id: schoolId }, function(data) {
                let options = '<option value="">{{ __("Choose Class...") }}</option>';
                data.forEach(grade => {
                    options += `<option value="${grade.id}">${grade.name}</option>`;
                });
                $gradeSelect.html(options).trigger('change.select2');
            });
        });

        $('#branch_id').change(function() {
            let branchId = $(this).val();
            let schoolId = $('#school_id').val() || "{{ auth()->user()->school_id }}";
            let $gradeSelect = $('#grade_id');
            
            $gradeSelect.html('<option value="">{{ __("Choose Class...") }}</option>');
            $('#section_id').html('<option value="">{{ __("Choose Section...") }}</option>');

            if(!branchId && !schoolId) return;

            $.get("{{ route('grades.index') }}", { school_id: schoolId, branch_id: branchId }, function(data) {
                let options = '<option value="">{{ __("Choose Class...") }}</option>';
                data.forEach(grade => {
                    options += `<option value="${grade.id}">${grade.name}</option>`;
                });
                $gradeSelect.html(options).trigger('change.select2');
            });
        });

        $('#grade_id').change(function() {
            let gradeId = $(this).val();
            let $sectionSelect = $('#section_id');
            
            $sectionSelect.html('<option value="">{{ __("Choose Section...") }}</option>');

            if(!gradeId) return;

            $.get("{{ route('sections.index') }}", { grade_id: gradeId }, function(data) {
                let options = '<option value="">{{ __("Choose Section...") }}</option>';
                data.forEach(sec => {
                    options += `<option value="${sec.id}">${sec.name}</option>`;
                });
                $sectionSelect.html(options).trigger('change.select2');
            });
        });
    });
</script>
@stop
