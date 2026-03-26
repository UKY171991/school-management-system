@extends('adminlte::page')

@section('title', __('Sections Management'))

@section('content_header')
    <h1>{{ __('Class Sections') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Card -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter"></i> {{ __('Search Filter') }}</h3>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <div class="row">
                        @if(auth()->user()->isMasterAdmin())
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Admin Name / School') }}</label>
                                <select class="form-control select2" id="filter_school_id" name="school_id">
                                    <option value="">{{ __('All Admins') }}</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Class / Grade') }}</label>
                                <select class="form-control select2" id="filter_grade_id" name="grade_id">
                                    <option value="">{{ __('All Classes') }}</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-block">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> {{ __('Search') }}
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="resetFilter">
                                        <i class="fas fa-undo"></i> {{ __('Reset') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('All Sections') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="newSectionBtn">
                        <i class="fas fa-plus"></i> {{ __('Add Section') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="sectionsTable">
                    <thead>
                        <tr>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Class/Grade') }}</th>
                            <th>{{ __('Section Name') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="sectionList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Section Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="sectionForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Section') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="section_id">
                    @if(auth()->user()->isMasterAdmin())
                    <div class="form-group">
                        <label>{{ __('Select Admin Name') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Select Admin') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="school_id" id="school_id" value="{{ auth()->user()->school_id }}">
                    @endif
                    <div class="form-group">
                        <label>{{ __('Select Grade/Class') }}</label>
                        <select class="form-control select2" name="grade_id" id="grade_id" required style="width: 100%;">
                            <option value="">{{ __('Select Class') }}</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Section Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="{{ __('e.g. Section A') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Section') }}</button>
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
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Initialize DataTable
        let table = $('#sectionsTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('sections.index') }}",
                data: function (d) {
                    d.grade_id = $('#filter_grade_id').val();
                    d.school_id = $('#filter_school_id').val();
                },
                dataSrc: ""
            },
            columns: [
                {
                    data: 'school',
                    name: 'school',
                    render: function(data) {
                        return data ? `<span class="badge badge-light border">${data.name}</span>` : '-';
                    }
                },
                {
                    data: 'grade',
                    name: 'grade',
                    render: function(data) {
                        return data ? data.name : "{{ __('N/A') }}";
                    }
                },
                {data: 'name', name: 'name'},
                {
                    data: 'id',
                    render: function(data) {
                        return `
                            <button class="btn btn-warning btn-sm editSection" data-id="${data}" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm deleteSection" data-id="${data}" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            rowId: function(a) { return 'section_' + a.id; }
        });

        $('#filterForm').submit(function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        $('#resetFilter').click(function() {
            $('#filterForm').trigger("reset");
            $('#filter_grade_id').val('').trigger('change');
            $('#filter_school_id').val('').trigger('change');
            table.ajax.reload();
        });

        $('#newSectionBtn').click(function() {
            $('#sectionForm').trigger("reset");
            $('.select2').val('').trigger('change');
            @if(auth()->user()->isMasterAdmin())
                $('#school_id').val('').trigger('change');
            @endif
            $('#section_id').val('');
            $('#modalTitle').text("{{ __('Add Section') }}");
            $('#sectionModal').modal('show');
        });

        $('#sectionForm').submit(function(e) {
            e.preventDefault();
            let id = $('#section_id').val();
            let url = id ? `/admin/sections/${id}` : "{{ route('sections.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#sectionModal').modal('hide');
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

        $('body').on('click', '.editSection', function() {
            let id = $(this).data('id');
            $.get(`/admin/sections/${id}`, function(data) {
                $('#section_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#grade_id').val(data.grade_id).trigger('change');
                $('#name').val(data.name);
                $('#modalTitle').text("{{ __('Edit Section') }}");
                $('#sectionModal').modal('show');
            });
        });

        $('body').on('click', '.deleteSection', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: "{{ __('Delete this section?') }}",
                text: "{{ __('This action cannot be undone.') }}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/admin/sections/${id}`,
                        type: "DELETE",
                        success: function (data) {
                            table.ajax.reload();
                            Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                        }
                    });
                }
            });
        });
    });
</script>
@stop

<style>
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
</style>
