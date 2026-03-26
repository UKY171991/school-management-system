@extends('adminlte::page')

@section('title', __('Exam Types'))

@section('content_header')
    <h1>{{ __('Exam Types') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Exam Types List') }}</h3>
                <div class="card-tools d-flex align-items-center">
                     <div class="mr-2" style="width: 200px;">
                        <select class="form-control select2" id="filter_school_id">
                            <option value="">{{ __('Select School') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="newTypeBtn">
                        <i class="fas fa-plus"></i> {{ __('Add Type') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="typesTable">
                    <thead>
                        <tr>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="typeList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="typeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="typeForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Exam Type') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="type_id">
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
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="{{ __('e.g. Determine, Yearly') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
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

        $('#filter_school_id').select2();
        $('#typeModal .select2').select2({ dropdownParent: $('#typeModal') });

        let table = $('#typesTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('exam-types.index') }}",
                data: function(d) {
                    d.school_id = $('#filter_school_id').val();
                },
                dataSrc: ""
            },
            language: {
                emptyTable: "{{ __('No Found Records') }}"
            },
            columns: [
                {
                    data: 'school',
                    render: function(data) {
                        return data ? `<span class="badge badge-light border">${data.name}</span>` : '-';
                    }
                },
                { data: 'name' },
                { data: 'description' },
                {
                    data: 'id',
                    render: function(data) {
                        return `
                            <button class="btn btn-xs btn-warning editType" data-id="${data}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-xs btn-danger deleteType" data-id="${data}"><i class="fas fa-trash"></i></button>
                        `;
                    },
                     orderable: false, searchable: false
                }
            ]
        });

        $('#filter_school_id').change(function() {
            table.ajax.reload();
        });

        $('#newTypeBtn').click(function() {
            $('#typeForm').trigger("reset");
            $('#type_id').val('');
            $('#school_id').val('').trigger('change');
            $('#modalTitle').text("{{ __('Add Exam Type') }}");
            $('#typeModal').modal('show');
        });

        $('#typeForm').submit(function(e) {
            e.preventDefault();
            let id = $('#type_id').val();
            let url = id ? `/admin/exam-types/${id}` : "{{ route('exam-types.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#typeModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            if(key === 'duplicate') {
                                errorMsg = value[0];
                            } else {
                                errorMsg += value[0] + '<br>';
                            }
                        });
                        Swal.fire("{{ __('Error') }}", errorMsg, 'error');
                    } else {
                        Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                    }
                }
            });
        });

        $('body').on('click', '.editType', function() {
            let id = $(this).data('id');
            $.get(`/admin/exam-types/${id}`, function(data) {
                $('#type_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#modalTitle').text("{{ __('Edit Exam Type') }}");
                $('#typeModal').modal('show');
            });
        });

        $('body').on('click', '.deleteType', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this exam type?') }}")) {
                $.ajax({
                    url: `/admin/exam-types/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        table.ajax.reload();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
<style>
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
</style>
@stop
