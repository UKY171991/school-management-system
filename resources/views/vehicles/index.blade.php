@extends('adminlte::page')

@section('title', __('Vehicles'))

@section('content_header')
    <h1>{{ __('Vehicle Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">{{ __('List of Vehicles') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm" id="newVehicleBtn">
                        <i class="fas fa-plus"></i> {{ __('Add New Vehicle') }}
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
                            <th>{{ __('Vehicle Number') }}</th>
                            <th>{{ __('Capacity') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="vehicleList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Modal -->
<div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="vehicleForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Vehicle') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="vehicle_id">
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
                        <label>{{ __('Vehicle Number') }}</label>
                        <input type="text" class="form-control" name="vehicle_number" id="vehicle_number" required placeholder="{{ __('e.g. ABC-1234') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Capacity (Seats)') }}</label>
                        <input type="number" class="form-control" name="capacity" id="capacity" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-info">{{ __('Save Vehicle') }}</button>
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

        function loadVehicles() {
            let schoolId = $('#filter_school_id').val();
            $.get("{{ route('vehicles.index') }}", { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(v => {
                    let schoolName = v.school ? v.school.name : '-';
                    rows += `
                        <tr id="vehicle_${v.id}">
                            <td><span class="badge badge-light border">${schoolName}</span></td>
                            <td>${v.vehicle_number}</td>
                            <td>${v.capacity}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editVehicle" data-id="${v.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteVehicle" data-id="${v.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#vehicleList').html(rows);
            });
        }

        loadVehicles();

        $('#filter_school_id').change(function() {
            loadVehicles();
        });

        $('#newVehicleBtn').click(function() {
            $('#vehicleForm').trigger("reset");
            $('#vehicle_id').val('');
            $('#school_id').val('').trigger('change');
            $('#modalTitle').text("{{ __('Add Vehicle') }}");
            $('#vehicleModal').modal('show');
        });

        $('#vehicleForm').submit(function(e) {
            e.preventDefault();
            let id = $('#vehicle_id').val();
            let url = id ? `/vehicles/${id}` : "{{ route('vehicles.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#vehicleModal').modal('hide');
                    loadVehicles();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    for (let key in errors) {
                        errorMsg += errors[key][0] + '<br>';
                    }
                    Swal.fire("{{ __('Error') }}", errorMsg, 'error');
                }
            });
        });

        $('body').on('click', '.editVehicle', function() {
            let id = $(this).data('id');
            $.get(`/vehicles/${id}`, function(data) {
                $('#vehicle_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#vehicle_number').val(data.vehicle_number);
                $('#capacity').val(data.capacity);
                $('#modalTitle').text("{{ __('Edit Vehicle') }}");
                $('#vehicleModal').modal('show');
            });
        });

        $('body').on('click', '.deleteVehicle', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this vehicle?') }}")) {
                $.ajax({
                    url: `/vehicles/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#vehicle_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
