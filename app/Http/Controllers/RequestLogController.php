<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestLog;
use Illuminate\Support\Facades\DB;

class RequestLogController extends Controller
{
    public function index()
    {
        // Group by path and count requests
        $requestStats = RequestLog::select('path', 'method', DB::raw('count(*) as count'))
            ->groupBy('path', 'method')
            ->orderBy('count', 'desc')
            ->get();

        return view('report.index', compact('requestStats'));
    }

    public function show($id)
    {
        $requestLog = RequestLog::findOrFail($id);
        return view('report.show', compact('requestLog'));
    }

    public function pathDetails(Request $request)
    {
        $path = $request->input('path');
        $method = $request->input('method');

        $requests = RequestLog::where('path', $path)
            ->when($method, function ($query, $method) {
                return $query->where('method', $method);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('report.path-details', compact('requests', 'path', 'method'));
    }

    public function clear()
    {
        RequestLog::truncate();
        return redirect('/report')->with('success', 'All request logs have been cleared successfully!');
    }
}
