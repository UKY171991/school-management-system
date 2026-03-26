@extends('adminlte::page')

@section('title', __('API Documentation'))

@section('content_header')
    <h1>{{ __('API Documentation') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- School Data API -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title text-bold"><i class="fas fa-school mr-2"></i> {{ __('Public School Data API') }}</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ __('Use these endpoints to fetch school-specific content for custom child domains.') }}</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 15%">{{ __('Endpoint') }}</th>
                                <th style="width: 10%">{{ __('Method') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Example URL') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>/api/schools/list</code></td>
                                <td><span class="badge badge-success">GET</span></td>
                                <td>{{ __('Returns a list of all schools that have a custom domain configured.') }}</td>
                                <td>
                                    <a href="{{ url('/api/schools/list') }}" target="_blank" class="text-primary truncate">
                                        {{ url('/api/schools/list') }} <i class="fas fa-external-link-alt ml-1 small"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><code>/api/schools/by-domain</code></td>
                                <td><span class="badge badge-success">GET</span></td>
                                <td>
                                    {{ __('Fetch specific school details by passing its domain name.') }}
                                    <br>
                                    <strong>{{ __('Params') }}:</strong> <code>domain</code>
                                </td>
                                <td>
                                    @php 
                                        $exampleDomain = \App\Models\School::whereNotNull('domain_name')->first()->domain_name ?? 'example.com';
                                        $exampleUrl = url("/api/schools/by-domain?domain=$exampleDomain");
                                    @endphp
                                    <a href="{{ $exampleUrl }}" target="_blank" class="text-primary truncate">
                                        {{ $exampleUrl }} <i class="fas fa-external-link-alt ml-1 small"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Response Format Example -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-code mr-2"></i> {{ __('Response Format (by-domain)') }}</h3>
            </div>
            <div class="card-body p-0">
<pre class="m-0 bg-dark p-3 rounded" style="color: #61afef;">
{
  <span style="color: #e06c75;">"info"</span>: {
    <span style="color: #e06c75;">"name"</span>: <span style="color: #98c379;">"Your School Name"</span>,
    <span style="color: #e06c75;">"address"</span>: <span style="color: #98c379;">"123 Street Address"</span>,
    <span style="color: #e06c75;">"phone"</span>: <span style="color: #98c379;">"0123456789"</span>,
    <span style="color: #e06c75;">"email"</span>: <span style="color: #98c379;">"school@email.com"</span>,
    <span style="color: #e06c75;">"logo_url"</span>: <span style="color: #98c379;">"https://domain.com/storage/logo.png"</span>,
    <span style="color: #e06c75;">"signature_url"</span>: <span style="color: #98c379;">"https://domain.com/storage/signature.png"</span>,
    <span style="color: #e06c75;">"domain"</span>: <span style="color: #98c379;">"school.devloper.space"</span>
  }
}
</pre>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .truncate {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: bottom;
    }
</style>
@stop
