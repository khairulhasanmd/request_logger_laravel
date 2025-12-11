<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestLogController;

Route::get('/', function () {
    return redirect('/report');
});

// Report routes
Route::get('/report', [RequestLogController::class, 'index']);
Route::get('/report/path', [RequestLogController::class, 'pathDetails']);
Route::post('/report/clear', [RequestLogController::class, 'clear']);
Route::get('/report/{id}', [RequestLogController::class, 'show']);

// Catch-all route for capturing any other requests
Route::any('{any}', function () {
    return response()->json([
        'status' => 'captured',
        'message' => 'Your request has been logged successfully'
    ]);
})->where('any', '.*');
