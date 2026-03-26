@extends('adminlte::page')

@section('title', __('Syllabus Upload'))

@section('content_header')
    <h1>{{ __('Academic Syllabus Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Uploaded Syllabi') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="uploadSyllabusBtn">
                        <i class="fas fa-upload"></i> {{ __('Upload Syllabus') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-control select2" id="filter_school_id">
                            <option value="">{{ __('All Schools') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Class/Section') }}</th>
                            <th>{{ __('Date Uploaded') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="syllabusList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="syllabusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="syllabusForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Upload New Syllabus') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('School') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Select School') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Syllabus Title') }}</label>
                        <input type="text" class="form-control" name="title" required placeholder="e.g. Mid-term Science Syllabus">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Subject') }}</label>
                        <select class="form-control" name="subject_id" required>
                            @foreach(\App\Models\Subject::all() as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Class & Section') }}</label>
                        <select class="form-control" name="section_id" required>
                            @foreach(\App\Models\Section::with('grade')->get() as $sec)
                                <option value="{{ $sec->id }}">{{ $sec->grade->name }} - {{ $sec->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('File (PDF, Doc, Image)') }}</label>
                        <input type="file" class="form-control" name="syllabus_file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
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

         // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        function loadSyllabi() {
            let schoolId = $('#filter_school_id').val();
            $.get("{{ route('syllabus.index') }}", { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(s => {
                    let schoolName = s.school ? s.school.name : '-';
                    rows += `
                        <tr id="syllabus_${s.id}">
                            <td><span class="badge badge-light border">${schoolName}</span></td>
                            <td>${s.title}</td>
                            <td>${s.subject.name}</td>
                            <td>${s.section.grade.name} - ${s.section.name}</td>
                            <td>${new Date(s.created_at).toLocaleDateString()}</td>
                            <td>
                                <a href="${s.file_url}" target="_blank" class="btn btn-xs btn-info"><i class="fas fa-eye"></i> {{ __('View') }}</a>
                                <button class="btn btn-xs btn-danger deleteSyllabus" data-id="${s.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#syllabusList').html(rows);
            });
        }

        loadSyllabi();

        $('#filter_school_id').change(function() {
            loadSyllabi();
        });

        $('#uploadSyllabusBtn').click(function() { 
            $('#syllabusForm').trigger("reset");
            $('#school_id').val('').trigger('change');
            $('#syllabusModal').modal('show'); 
        });

        $('#syllabusForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('syllabus.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#syllabusModal').modal('hide');
                    $('#syllabusForm').trigger("reset");
                    // loadSyllabi(); // Using hard reload instead for reliability
                    window.location.reload();
                }
            });
        });

        $('body').on('click', '.deleteSyllabus', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this syllabus?') }}")) {
                $.ajax({
                    url: `/syllabus/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#syllabus_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
