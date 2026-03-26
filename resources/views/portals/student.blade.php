@extends('adminlte::page')

@section('title', __('Student Portal'))

@section('content_header')
    <h1>{{ __('Student Portal Dashboard') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ __('Attendance') }}</h3>
                <p>{{ __('95% Average') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ __('Exams') }}</h3>
                <p>{{ __('Upcoming: Mid-term') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ __('Fees') }}</h3>
                <p>{{ __('Status: Paid') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ __('Homework') }}</h3>
                <p>{{ __('3 Pending') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title">{{ __('My Recent Activity') }}</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">{{ __('No recent activities found.') }}</p>
    </div>
</div>
@stop
