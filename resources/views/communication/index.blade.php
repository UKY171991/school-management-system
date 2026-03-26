@extends('adminlte::page')

@section('title', __('Communication Console'))

@section('content_header')
    <h1>{{ __('Communication & Alerts') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Send Announcement') }}</h3>
            </div>
            <div class="card-body">
                <form id="commForm">
                    <div class="form-group">
                        <label>{{ __('Receiver Type') }}</label>
                        <select class="form-control" name="type" required>
                            <option value="all">{{ __('All Users') }}</option>
                            <option value="teachers">{{ __('Teachers Only') }}</option>
                            <option value="parents">{{ __('Parents Only') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Method') }}</label>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="sms" name="method[]" value="sms">
                            <label for="sms" class="custom-control-label">{{ __('SMS / WhatsApp') }}</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="email" name="method[]" value="email" checked>
                            <label for="email" class="custom-control-label">{{ __('Email Notification') }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Message Subject') }}</label>
                        <input type="text" class="form-control" name="subject" required placeholder="{{ __('Important Update') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Message Content') }}</label>
                        <textarea class="form-control" name="message" rows="5" required placeholder="{{ __('Type your message here...') }}"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Broadcast Message') }}</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">{{ __('Recent Broadcasts') }}</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Target') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody id="broadcastList">
                        <tr>
                            <td colspan="3" class="text-center text-muted">{{ __('No recent broadcasts found.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $('#commForm').submit(function(e) {
            e.preventDefault();
            let methods = $("input[name='method[]']:checked").length;
            if(methods === 0) {
                Swal.fire("{{ __('Error') }}", "{{ __('Please select at least one notification method.') }}", 'error');
                return;
            }

            $.ajax({
                url: "{{ route('communication.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    Swal.fire("{{ __('Sent!') }}", data.success, 'success');
                    $('#commForm').trigger("reset");
                }
            });
        });
    });
</script>
@stop
