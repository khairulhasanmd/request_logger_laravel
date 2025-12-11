@extends('layouts.app')

@section('title', 'Request Report - Webhook Capture')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Request Report</h1>
        <p class="mt-2 text-gray-600">View all captured HTTP requests grouped by endpoint</p>
    </div>
    @if($requestStats->count() > 0)
    <form action="/report/clear" method="POST" onsubmit="return confirm('Are you sure you want to clear all request logs? This action cannot be undone.');">
        @csrf
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow-sm transition-colors flex items-center space-x-2">
            <i class="fas fa-trash-alt"></i>
            <span>Clear All Logs</span>
        </button>
    </form>
    @endif
</div>

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
    <i class="fas fa-check-circle mr-3 text-green-600"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if($requestStats->count() > 0)
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-900">
            <i class="fas fa-list"></i> Captured Endpoints
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Method
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Path
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Request Count
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($requestStats as $stat)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge badge-{{ strtolower($stat->method) }}">
                            {{ $stat->method }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <code class="text-sm text-gray-900 font-mono">{{ $stat->path ?: '/' }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-hashtag mr-1"></i> {{ $stat->count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="/report/path?path={{ urlencode($stat->path) }}&method={{ $stat->method }}" 
                           class="text-blue-600 hover:text-blue-900 font-medium">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white shadow-md rounded-lg p-12 text-center">
    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
        <i class="fas fa-inbox text-6xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">No requests captured yet</h3>
    <p class="text-gray-600 mb-6">Send a request to any endpoint to start capturing data</p>
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-w-2xl mx-auto">
        <p class="text-sm text-gray-700 font-medium mb-2">Try it out:</p>
        <code class="text-sm text-gray-900 block bg-white p-3 rounded border">
            curl -X POST {{ url('/test') }} -d "hello=world"
        </code>
    </div>
</div>
@endif

<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h3 class="text-lg font-semibold text-blue-900 mb-2">
        <i class="fas fa-info-circle"></i> How it works
    </h3>
    <p class="text-blue-800 mb-4">
        All HTTP requests (GET, POST, PUT, DELETE, etc.) sent to any path on this domain will be automatically captured and logged.
    </p>
    <ul class="list-disc list-inside text-blue-800 space-y-1 text-sm">
        <li>View request counts by endpoint</li>
        <li>Inspect headers, parameters, and body data</li>
        <li>See timestamps and client information</li>
        <li>Perfect for webhook testing and debugging</li>
    </ul>
</div>
@endsection
