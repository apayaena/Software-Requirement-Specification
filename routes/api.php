<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\IncidentController;
use App\Models\Location;
use App\Models\Department;

Route::get('/locations', function () {
    return response()->json(Location::all());
});

Route::get('/departments', function () {
    return response()->json(Department::all());
});

Route::prefix('incidents')->group(function () {
    Route::get('/', [IncidentController::class, 'index']);                      // List all incidents
    Route::get('/{id}', [IncidentController::class, 'show']);                  // Detail of a specific incident
    Route::post('/', [IncidentController::class, 'store']);                    // Fase 1: Report Incident
    Route::put('/{id}/review', [IncidentController::class, 'review']);          // Fase 2: Classify Severity
    Route::put('/{id}/investigate', [IncidentController::class, 'investigate']); // Fase 3: Root Cause Analysis (5 Whys)
    Route::post('/{id}/capa', [IncidentController::class, 'assignCapa']);       // Fase 4: Assign CAPA Task
    Route::put('/capa/{taskId}/complete', [IncidentController::class, 'completeCapa']); // Fase 4 Part 2: Upload completion proof
    Route::put('/{id}/close', [IncidentController::class, 'close']);            // Fase 5: Verify & Close Incident
});
