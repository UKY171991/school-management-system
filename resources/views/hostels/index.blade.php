@extends('adminlte::page')

@section('title', __('Hostels'))

@section('content_header')
    <h1>{{ __('Hostel Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-teal">
            <div class="card-header">
                <h3 class="card-title">{{ __('List of Hostels') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-teal btn-sm" id="newHostelBtn" style="background-color: #20c997; color: white;">
                        <i class="fas fa-plus"></i> {{ __('Add New Hostel') }}
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
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th>{{ __('Capacity') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="hostelList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Hostel Modal -->
<div class="modal fade" id="hostelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="hostelForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Hostel') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="hostel_id">
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
                        <label>{{ __('Hostel Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Type') }}</label>
                        <select class="form-control" name="type" id="type" required>
                            <option value="Boys">{{ __('Boys') }}</option>
                            <option value="Girls">{{ __('Girls') }}</option>
                            <option value="Common">{{ __('Common') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Address') }}</label>
                        <textarea class="form-control" name="address" id="address" required rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Total Capacity (Beds)') }}</label>
                        <input type="number" class="form-control" name="capacity" id="capacity" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-teal" style="background-color: #20c997; color: white;">{{ __('Save Hostel') }}</button>
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

        var baseUrl = "{{ route('hostels.index') }}";

        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        function loadHostels() {
            let schoolId = $('#filter_school_id').val();
            $.get(baseUrl, { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(h => {
                    let schoolName = h.school ? h.school.name : '-';
                    rows += `
                        <tr id="hostel_${h.id}">
                            <td><span class="badge badge-light border">${schoolName}</span></td>
                            <td>${h.name}</td>
                            <td>${h.type}</td>
                            <td>${h.address}</td>
                            <td>${h.capacity}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editHostel" data-id="${h.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteHostel" data-id="${h.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#hostelList').html(rows);
            });
        }

        loadHostels();

        $('#filter_school_id').change(function() {
            loadHostels();
        });

        $('#newHostelBtn').click(function() {
            $('#hostelForm').trigger("reset");
            $('#hostel_id').val('');
            $('#school_id').val('').trigger('change');
            $('#modalTitle').text("{{ __('Add Hostel') }}");
            $('#hostelModal').modal('show');
        });

        $('#hostelForm').submit(function(e) {
            e.preventDefault();
            let id = $('#hostel_id').val();
            let url = id ? baseUrl + '/' + id : baseUrl;
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#hostelModal').modal('hide');
                    loadHostels();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                }
            });
        });

        $('body').on('click', '.editHostel', function() {
            let id = $(this).data('id');
            $.get(baseUrl + '/' + id, function(data) {
                $('#hostel_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#name').val(data.name);
                $('#type').val(data.type);
                $('#address').val(data.address);
                $('#capacity').val(data.capacity);
                $('#modalTitle').text("{{ __('Edit Hostel') }}");
                $('#hostelModal').modal('show');
            });
        });

        $('body').on('click', '.deleteHostel', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this hostel? All associated rooms might be affected.') }}")) {
                $.ajax({
                    url: baseUrl + '/' + id,
                    type: "DELETE",
                    success: function(data) {
                        $(`#hostel_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
