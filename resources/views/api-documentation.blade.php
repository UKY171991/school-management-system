@extends('adminlte::page')

@section('title', __('API Documentation'))

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-code mr-2"></i>{{ __('API Documentation') }}
            </h1>
            <p class="text-muted">{{ __('Complete API reference for school data access') }}</p>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>
@stop

@section('content')
<style>
    .api-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }
    .api-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }
    .api-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.5rem;
    }
    .method-badge {
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }
    .method-get { background: #28a745; color: white; }
    .method-post { background: #007bff; color: white; }
    .method-put { background: #ffc107; color: #333; }
    .method-delete { background: #dc3545; color: white; }
    
    .endpoint-item {
        padding: 1.25rem;
        border-left: 4px solid #667eea;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }
    .endpoint-item:hover {
        background: #e9ecef;
        border-left-color: #764ba2;
    }
    .code-block {
        background: #2d3748;
        color: #68d391;
        padding: 1rem;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        overflow-x: auto;
        margin-top: 0.5rem;
    }
    .copy-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
    }
    .info-box-custom {
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid;
    }
    .info-box-primary { background: #e3f2fd; border-color: #2196f3; }
    .info-box-success { background: #e8f5e9; border-color: #4caf50; }
    .info-box-warning { background: #fff3e0; border-color: #ff9800; }
    
    .tab-content {
        padding: 1.5rem;
    }
</style>

<div class="row">
    <div class="col-12">
        <!-- Quick Start Guide -->
        <div class="info-box-custom info-box-primary">
            <h4><i class="fas fa-rocket mr-2"></i>{{ __('Quick Start') }}</h4>
            <p class="mb-2">{{ __('To use the API, you need to provide your school domain in the request:') }}</p>
            <ul class="mb-0">
                <li><strong>{{ __('Method 1 (Header):') }}</strong> <code>X-School-Domain: school.developer.space</code></li>
                <li><strong>{{ __('Method 2 (Query):') }}</strong> <code>?domain=school.developer.space</code></li>
                <li><strong>{{ __('Method 3 (Auto):') }}</strong> {{ __('Access from your custom domain') }}</li>
            </ul>
        </div>

        <!-- Base URL -->
        <div class="info-box-custom info-box-success">
            <h4><i class="fas fa-link mr-2"></i>{{ __('Base URL') }}</h4>
            <div class="position-relative">
                <div class="code-block">{{ url('/api/public-api') }}</div>
                <button class="btn btn-sm btn-light copy-btn" onclick="copyToClipboard('{{ url('/api/public-api') }}')">
                    <i class="fas fa-copy"></i> {{ __('Copy') }}
                </button>
            </div>
        </div>

        <!-- API Endpoints by Category -->
        @foreach($endpoints as $category => $categoryEndpoints)
        <div class="api-card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-folder-open mr-2"></i>{{ __($category) }}
                </h3>
            </div>
            <div class="card-body">
                @foreach($categoryEndpoints as $endpoint)
                <div class="endpoint-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                {{ $endpoint['method'] }}
                            </span>
                            <code class="ml-2" style="font-size: 1rem;">{{ $endpoint['endpoint'] }}</code>
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="testEndpoint('{{ $endpoint['example'] }}')">
                            <i class="fas fa-play mr-1"></i> {{ __('Test') }}
                        </button>
                    </div>
                    
                    <p class="mb-2 text-muted">{{ __($endpoint['description']) }}</p>
                    
                    <div class="mb-2">
                        <strong>{{ __('Parameters:') }}</strong> 
                        <span class="text-muted">{{ $endpoint['parameters'] }}</span>
                    </div>
                    
                    <div class="position-relative">
                        <strong>{{ __('Example:') }}</strong>
                        <div class="code-block">{{ $endpoint['example'] }}</div>
                        <button class="btn btn-sm btn-light copy-btn" onclick="copyToClipboard('{{ $endpoint['example'] }}')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- Code Examples -->
        <div class="api-card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-code mr-2"></i>{{ __('Code Examples') }}
                </h3>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#curl">cURL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#javascript">JavaScript</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#php">PHP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#python">Python</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="curl" class="tab-pane fade show active">
                        <div class="position-relative">
                            <div class="code-block">curl -X GET "{{ url('/api/public-api/info') }}?domain=school.developer.space" \
  -H "Accept: application/json"</div>
                            <button class="btn btn-sm btn-light copy-btn" onclick="copyToClipboard('curl -X GET &quot;{{ url('/api/public-api/info') }}?domain=school.developer.space&quot; -H &quot;Accept: application/json&quot;')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div id="javascript" class="tab-pane fade">
                        <div class="position-relative">
                            <div class="code-block">fetch('{{ url('/api/public-api/students') }}', {
  headers: {
    'X-School-Domain': 'school.developer.space'
  }
})
.then(response => response.json())
.then(data => console.log(data));</div>
                            <button class="btn btn-sm btn-light copy-btn" onclick="copyToClipboard(`fetch('{{ url('/api/public-api/students') }}', {\n  headers: {\n    'X-School-Domain': 'school.developer.space'\n  }\n})\n.then(response => response.json())\n.then(data => console.log(data));`)">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div id="php" class="tab-pane fade">
                        <div class="position-relative">
                            <div class="code-block">$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ url('/api/public-api/info') }}?domain=school.developer.space');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);</div>
                            <button class="btn btn-sm btn-light copy-btn" onclick="copyToClipboard(`$ch = curl_init();\ncurl_setopt($ch, CURLOPT_URL, '{{ url('/api/public-api/info') }}?domain=school.developer.space');\ncurl_setopt($ch, CURLOPT_RETURNTRANSFER, true);\n$response = curl_exec($ch);\ncurl_close($ch);\n$data = json_decode($response, true);`)">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div id="python" class="tab-pane fade">
                        <div class="position-relative">
                            <div class="code-block">import requests

headers = {'X-School-Domain': 'school.developer.space'}
response = requests.get('{{ url('/api/public-api/students') }}', headers=headers)
data = response.json()
print(data)</div>
                            <button class="btn btn-sm btn-light copy-btn" onclick="copyToClipboard(`import requests\n\nheaders = {'X-School-Domain': 'school.developer.space'}\nresponse = requests.get('{{ url('/api/public-api/students') }}', headers=headers)\ndata = response.json()\nprint(data)`)">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Examples -->
        <div class="api-card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-file-code mr-2"></i>{{ __('Response Examples') }}
                </h3>
            </div>
            <div class="card-body">
                <h5>{{ __('Success Response:') }}</h5>
                <div class="code-block">{
  "success": true,
  "data": {
    "id": 2,
    "name": "Testing School",
    "address": "Jaunpur Rd",
    "phone": "09453619260",
    "email": "umakant171991@gmail.com",
    "domain_name": "school.developer.space"
  }
}</div>

                <h5 class="mt-4">{{ __('Error Response:') }}</h5>
                <div class="code-block">{
  "error": "School not found"
}</div>
            </div>
        </div>

        <!-- API Tester -->
        <div class="api-card">
            <div class="card-header bg-gradient-success">
                <h3 class="card-title mb-0">
                    <i class="fas fa-flask mr-2"></i>{{ __('API Tester') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>{{ __('School Domain:') }}</label>
                    <input type="text" class="form-control" id="testDomain" placeholder="school.developer.space" value="school.developer.space">
                </div>
                <div class="form-group">
                    <label>{{ __('Endpoint:') }}</label>
                    <select class="form-control" id="testEndpoint">
                        <option value="/api/public-api/info">GET /api/public-api/info</option>
                        <option value="/api/public-api/statistics">GET /api/public-api/statistics</option>
                        <option value="/api/public-api/students">GET /api/public-api/students</option>
                        <option value="/api/public-api/teachers">GET /api/public-api/teachers</option>
                        <option value="/api/public-api/grades">GET /api/public-api/grades</option>
                        <option value="/api/public-api/branches">GET /api/public-api/branches</option>
                    </select>
                </div>
                <button class="btn btn-success btn-lg" onclick="testAPI()">
                    <i class="fas fa-play mr-2"></i>{{ __('Test API') }}
                </button>
                
                <div id="apiResponse" class="mt-3" style="display: none;">
                    <h5>{{ __('Response:') }}</h5>
                    <div class="code-block" id="responseContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ __("Copied to clipboard!") }}',
            showConfirmButton: false,
            timer: 2000
        });
    });
}

function testEndpoint(url) {
    window.open(url, '_blank');
}

function testAPI() {
    const domain = $('#testDomain').val();
    const endpoint = $('#testEndpoint').val();
    const url = `{{ url('') }}${endpoint}?domain=${domain}`;
    
    $('#apiResponse').show();
    $('#responseContent').html('<i class="fas fa-spinner fa-spin"></i> {{ __("Loading...") }}');
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            $('#responseContent').html(JSON.stringify(data, null, 2));
        })
        .catch(error => {
            $('#responseContent').html(`Error: ${error.message}`);
        });
}
</script>
@stop
