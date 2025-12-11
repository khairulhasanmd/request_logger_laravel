@extends('layouts.app')

@section('title', 'Path Details - Webhook Capture')

@section('content')
<div class="mb-6">
    <a href="/report" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
    </a>
    <h1 class="text-3xl font-bold text-gray-900 mt-4">Requests for Path</h1>
    <div class="mt-2 flex items-center space-x-3">
        <span class="badge badge-{{ strtolower($method) }}">{{ $method }}</span>
        <code class="text-xl text-gray-700 font-mono">{{ $path ?: '/' }}</code>
    </div>
</div>

@if($requests->count() > 0)
<div class="space-y-4">
    @foreach($requests as $request)
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <div>
                <span class="badge badge-{{ strtolower($request->method) }}">{{ $request->method }}</span>
                <span class="ml-3 text-sm text-gray-600">
                    <i class="fas fa-clock"></i> {{ $request->created_at->format('M d, Y H:i:s') }}
                </span>
            </div>
            <a href="/report/{{ $request->id }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                <i class="fas fa-external-link-alt"></i> Full Details
            </a>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">IP Address</h4>
                    <p class="text-sm text-gray-900">{{ $request->ip_address }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">User Agent</h4>
                    <p class="text-sm text-gray-900 truncate" title="{{ $request->user_agent }}">
                        {{ $request->user_agent ?: 'N/A' }}
                    </p>
                </div>
            </div>

            @if($request->query_params && count($request->query_params) > 0)
            <div class="mb-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Query Parameters</h4>
                <div class="code-block">
                    <pre class="text-xs">{{ json_encode($request->query_params, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            @if($request->post_data && count($request->post_data) > 0)
            <div class="mb-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">POST Data</h4>
                <div class="code-block">
                    <pre class="text-xs">{{ json_encode($request->post_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            @if($request->raw_body)
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Raw Body</h4>
                <div class="code-block">
                    <pre class="text-xs">{{ Str::limit($request->raw_body, 500) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $requests->links() }}
</div>
@else
<div class="bg-white shadow-md rounded-lg p-12 text-center">
    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
        <i class="fas fa-inbox text-6xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900">No requests found</h3>
    <p class="text-gray-600">No requests have been captured for this path yet.</p>
</div>
@endif
@endsection
