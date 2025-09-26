<?php

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

// API routes will be defined here

use App\Http\Controllers\Api\OrganizationController;
use App\Http\Middleware\ApiKeyMiddleware;

Route::middleware([ApiKeyMiddleware::class])->group(function () {
    Route::apiResource('organizations', OrganizationController::class);
    Route::get('buildings/{buildingId}/organizations', [OrganizationController::class, 'byBuilding']);
    Route::get('activities/{activityId}/organizations', [OrganizationController::class, 'byActivity']);
    Route::get('activities/{activityId}/organizations/recursive', [OrganizationController::class, 'searchByActivity']);
    Route::post('organizations/search/location', [OrganizationController::class, 'byLocation']);
    Route::post('organizations/search/name', [OrganizationController::class, 'searchByName']);
});
