@extends('adminlte::page')

@section('title', __('Teacher Portal'))

@section('content_header')
    <h1>{{ __('Teacher Portal Dashboard') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ __('Classes') }}</h3>
                <p>{{ __('Assigned Classes') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ __('Timetable') }}</h3>
                <p>{{ __('View Schedule') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-week"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ __('Attendance') }}</h3>
                <p>{{ __('Mark Attendance') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ __('Leave') }}</h3>
                <p>{{ __('Apply for Leave') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ __('Today\'s Schedule') }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('Time') }}</th>
                    <th>{{ __('Class') }}</th>
                    <th>{{ __('Subject') }}</th>
                    <th>{{ __('Room') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>09:00 AM - 10:00 AM</td>
                    <td>{{ __('Class 10-A') }}</td>
                    <td>{{ __('Mathematics') }}</td>
                    <td>{{ __('Room 101') }}</td>
                </tr>
                <tr>
                    <td>10:15 AM - 11:15 AM</td>
                    <td>{{ __('Class 9-B') }}</td>
                    <td>{{ __('Physics') }}</td>
                    <td>{{ __('Lab 2') }}</td>
                </tr>
                <tr>
                    <td>11:30 AM - 12:30 PM</td>
                    <td>{{ __('Class 8-A') }}</td>
                    <td>{{ __('Science') }}</td>
                    <td>{{ __('Room 105') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center text-muted">{{ __('Lunch Break') }}</td>
                </tr>
                <tr>
                    <td>01:30 PM - 02:30 PM</td>
                    <td>{{ __('Class 10-B') }}</td>
                    <td>{{ __('Mathematics') }}</td>
                    <td>{{ __('Room 102') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@stop
