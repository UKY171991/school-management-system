@extends('adminlte::page')

@section('title', __('Homework'))

@section('content_header')
    <h1>{{ __('Homework Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Assigned Homework') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="newHomeworkBtn">
                        <i class="fas fa-plus"></i> {{ __('Assign Homework') }}
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
                            <th>{{ __('Due Date') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="homeworkList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Homework Modal -->
<div class="modal fade" id="homeworkModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="homeworkForm">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Assign Homework') }}</h5>
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
                        <label>{{ __('Title') }}</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Due Date') }}</label>
                        <input type="date" class="form-control" name="due_date" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Subject') }}</label>
                        <select class="form-control" name="subject_id" required>
                            @foreach(\App\Models\Subject::all() as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Class/Section') }}</label>
                        <select class="form-control" name="section_id" required>
                            @foreach(\App\Models\Section::with('grade')->get() as $section)
                                <option value="{{ $section->id }}">{{ $section->grade->name }} - {{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Assignment') }}</button>
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

        function loadHomework() {
            let schoolId = $('#filter_school_id').val();
            $.get("{{ route('homework.index') }}", { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(h => {
                    let schoolName = h.school ? h.school.name : '-';
                    rows += `
                        <tr id="homework_${h.id}">
                            <td><span class="badge badge-light border">${schoolName}</span></td>
                            <td>${h.title}</td>
                            <td>${h.subject.name}</td>
                            <td>${h.section.grade.name} - ${h.section.name}</td>
                            <td>${h.due_date}</td>
                            <td>
                                <button class="btn btn-xs btn-danger deleteHomework" data-id="${h.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#homeworkList').html(rows);
            });
        }

        loadHomework();

        $('#filter_school_id').change(function() {
            loadHomework();
        });

        $('#newHomeworkBtn').click(function() {
            $('#homeworkForm').trigger("reset");
            $('#school_id').val('').trigger('change');
            $('#homeworkModal').modal('show');
        });

        $('#homeworkForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('homework.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    $('#homeworkModal').modal('hide');
                    loadHomework();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                }
            });
        });

        $('body').on('click', '.deleteHomework', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this assignment?') }}")) {
                $.ajax({
                    url: `/homework/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#homework_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
