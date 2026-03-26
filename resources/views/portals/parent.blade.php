@extends('adminlte::page')

@section('title', __('Parent Portal'))

@section('content_header')
    <h1>{{ __('Parent Portal Dashboard') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ __('Attendance') }}</h3>
                <p>{{ __('Child: John Doe (92%)') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ __('Academic Progress') }}</h3>
                <p>{{ __('Average Grade: A-') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-12">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ __('Fees Due') }}</h3>
                <p>{{ __('$200.00 Outstanding') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Notice Board') }}</h3>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>{{ __('Parent-Teacher Meeting') }}</strong><br>
                        <small class="text-muted">{{ __('Friday, 25th Jan') }}</small>
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Annual Sports Day') }}</strong><br>
                        <small class="text-muted">{{ __('Monday, 10th Feb') }}</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">{{ __('Quick Actions') }}</h3>
            </div>
            <div class="card-body">
                <a href="#" class="btn btn-app"><i class="fas fa-file-invoice-dollar"></i> {{ __('Pay Fees') }}</a>
                <a href="#" class="btn btn-app"><i class="fas fa-envelope"></i> {{ __('Message Teacher') }}</a>
                <a href="#" class="btn btn-app"><i class="fas fa-download"></i> {{ __('Report Card') }}</a>
            </div>
        </div>
    </div>
</div>
@stop
