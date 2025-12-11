@extends('layouts.app')

@section('title', 'Request Details - Webhook Capture')

@section('content')
<div class="mb-6">
    <a href="/report" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
    </a>
    <div class="flex justify-between items-start mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Request Details</h1>
            <p class="mt-2 text-gray-600">Request ID: #{{ $requestLog->id }}</p>
        </div>
        <span class="badge badge-{{ strtolower($requestLog->method) }} text-lg px-4 py-2">
            {{ $requestLog->method }}
        </span>
    </div>
</div>

<div class="space-y-6">
    <!-- Basic Information -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-info-circle"></i> Basic Information
            </h2>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">Timestamp</h4>
                    <p class="text-sm text-gray-900">
                        <i class="fas fa-calendar-alt text-gray-400"></i> {{ $requestLog->created_at->format('F d, Y') }}
                        <br>
                        <i class="fas fa-clock text-gray-400"></i> {{ $requestLog->created_at->format('H:i:s') }}
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">Method</h4>
                    <p class="text-sm"><span class="badge badge-{{ strtolower($requestLog->method) }}">{{ $requestLog->method }}</span></p>
                </div>
                <div class="md:col-span-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">Full URL</h4>
                    <div class="flex items-center space-x-2">
                        <code class="text-sm text-gray-900 bg-gray-50 p-2 rounded flex-1 break-all">{{ $requestLog->full_url }}</code>
                        <button onclick="copyToClipboard('{{ $requestLog->full_url }}')" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">Path</h4>
                    <code class="text-sm text-gray-900">{{ $requestLog->path }}</code>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-user"></i> Client Information
            </h2>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">IP Address</h4>
                    <p class="text-sm text-gray-900 font-mono">{{ $requestLog->ip_address }}</p>
                </div>
                <div class="md:col-span-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">User Agent</h4>
                    <p class="text-sm text-gray-900">{{ $requestLog->user_agent ?: 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Query Parameters -->
    @if($requestLog->query_params && count($requestLog->query_params) > 0)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-question-circle"></i> Query Parameters
            </h2>
            <button onclick="copyToClipboard(formatJson({{ json_encode($requestLog->query_params) }}))" 
                    class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
        <div class="px-6 py-4">
            <div class="code-block">
                <pre>{{ json_encode($requestLog->query_params, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
    @endif

    <!-- POST Data -->
    @if($requestLog->post_data && count($requestLog->post_data) > 0)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-database"></i> POST Data
            </h2>
            <button onclick="copyToClipboard(formatJson({{ json_encode($requestLog->post_data) }}))" 
                    class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
        <div class="px-6 py-4">
            <div class="code-block">
                <pre>{{ json_encode($requestLog->post_data, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
    @endif

    <!-- Raw Body -->
    @if($requestLog->raw_body)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-file-code"></i> Raw Body
            </h2>
            <button onclick="copyToClipboard('{{ addslashes($requestLog->raw_body) }}')" 
                    class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
        <div class="px-6 py-4">
            <div class="code-block">
                <pre>{{ $requestLog->raw_body }}</pre>
            </div>
        </div>
    </div>
    @endif

    <!-- Headers -->
    @if($requestLog->headers && count($requestLog->headers) > 0)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list"></i> Headers
            </h2>
            <button onclick="copyToClipboard(formatJson({{ json_encode($requestLog->headers) }}))" 
                    class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
        <div class="px-6 py-4">
            <div class="code-block">
                <pre class="text-xs">{{ json_encode($requestLog->headers, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
    @endif

    <!-- Server Variables -->
    @if($requestLog->server_vars && count($requestLog->server_vars) > 0)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-server"></i> Server Variables
            </h2>
        </div>
        <div class="px-6 py-4">
            <div class="code-block">
                <pre>{{ json_encode($requestLog->server_vars, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
