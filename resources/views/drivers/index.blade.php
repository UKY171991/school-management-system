@extends('adminlte::page')

@section('title', __('Drivers'))

@section('content_header')
    <h1>{{ __('Driver Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-dark">
            <div class="card-header">
                <h3 class="card-title">{{ __('List of Drivers') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-dark btn-sm" id="newDriverBtn">
                        <i class="fas fa-plus"></i> {{ __('Add New Driver') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('License Number') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="driverList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Driver Modal -->
<div class="modal fade" id="driverModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="driverForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Driver') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="driver_id">
                    <div class="form-group">
                        <label>{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Phone Number') }}</label>
                        <input type="text" class="form-control" name="phone" id="phone" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('License Number') }}</label>
                        <input type="text" class="form-control" name="license_number" id="license_number" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Save Driver') }}</button>
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

        function loadDrivers() {
            $.get("{{ route('drivers.index') }}", function (data) {
                let rows = '';
                data.forEach(d => {
                    rows += `
                        <tr id="driver_${d.id}">
                            <td>${d.name}</td>
                            <td>${d.phone}</td>
                            <td>${d.license_number}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editDriver" data-id="${d.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteDriver" data-id="${d.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#driverList').html(rows);
            });
        }

        loadDrivers();

        $('#newDriverBtn').click(function() {
            $('#driverForm').trigger("reset");
            $('#driver_id').val('');
            $('#modalTitle').text("{{ __('Add Driver') }}");
            $('#driverModal').modal('show');
        });

        $('#driverForm').submit(function(e) {
            e.preventDefault();
            let id = $('#driver_id').val();
            let url = id ? `/drivers/${id}` : "{{ route('drivers.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#driverModal').modal('hide');
                    loadDrivers();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                }
            });
        });

        $('body').on('click', '.editDriver', function() {
            let id = $(this).data('id');
            $.get(`/drivers/${id}`, function(data) {
                $('#driver_id').val(data.id);
                $('#name').val(data.name);
                $('#phone').val(data.phone);
                $('#license_number').val(data.license_number);
                $('#modalTitle').text("{{ __('Edit Driver') }}");
                $('#driverModal').modal('show');
            });
        });

        $('body').on('click', '.deleteDriver', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this driver?') }}")) {
                $.ajax({
                    url: `/drivers/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#driver_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
