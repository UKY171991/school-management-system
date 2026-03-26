@extends('adminlte::page')

@section('title', __('Transport Routes'))

@section('content_header')
    <h1>{{ __('Transport Route Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-indigo">
            <div class="card-header">
                <h3 class="card-title">{{ __('All Routes') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="newRouteBtn">
                        <i class="fas fa-plus"></i> {{ __('Add New Route') }}
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
                            <th>{{ __('Route Name') }}</th>
                            <th>{{ __('Vehicle') }}</th>
                            <th>{{ __('Driver') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="routeList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Route Modal -->
<div class="modal fade" id="routeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="routeForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Transport Route') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="route_id">
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
                        <label>{{ __('Route Name') }}</label>
                        <input type="text" class="form-control" name="route_name" id="route_name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Vehicle') }}</label>
                        <select class="form-control" name="vehicle_id" id="vehicle_id" required>
                            @foreach(\App\Models\Vehicle::all() as $v)
                                <option value="{{ $v->id }}">{{ $v->vehicle_number }} (Cap: {{ $v->capacity }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Driver') }}</label>
                        <select class="form-control" name="driver_id" id="driver_id" required>
                            @foreach(\App\Models\Driver::all() as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Route') }}</button>
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

        function loadRoutes() {
            let schoolId = $('#filter_school_id').val();
            $.get("{{ route('transport-routes.index') }}", { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(r => {
                    let schoolName = r.school ? r.school.name : '-';
                    rows += `
                        <tr id="route_${r.id}">
                            <td><span class="badge badge-light border">${schoolName}</span></td>
                            <td>${r.route_name}</td>
                            <td>${r.vehicle.vehicle_number}</td>
                            <td>${r.driver.name}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editRoute" data-id="${r.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteRoute" data-id="${r.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#routeList').html(rows);
            });
        }

        loadRoutes();

        $('#filter_school_id').change(function() {
            loadRoutes();
        });

        $('#newRouteBtn').click(function() {
            $('#routeForm').trigger("reset");
            $('#school_id').val('').trigger('change');
            $('#route_id').val('');
            $('#modalTitle').text("{{ __('Add Transport Route') }}");
            $('#routeModal').modal('show');
        });

        $('#routeForm').submit(function(e) {
            e.preventDefault();
            let id = $('#route_id').val();
            let url = id ? `/transport-routes/${id}` : "{{ route('transport-routes.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#routeModal').modal('hide');
                    loadRoutes();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                }
            });
        });

        $('body').on('click', '.editRoute', function() {
            let id = $(this).data('id');
            $.get(`/transport-routes/${id}`, function(data) {
                $('#route_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#route_name').val(data.route_name);
                $('#vehicle_id').val(data.vehicle_id);
                $('#driver_id').val(data.driver_id);
                $('#modalTitle').text("{{ __('Edit Transport Route') }}");
                $('#routeModal').modal('show');
            });
        });

        $('body').on('click', '.deleteRoute', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this transport route?') }}")) {
                $.ajax({
                    url: `/transport-routes/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#route_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
