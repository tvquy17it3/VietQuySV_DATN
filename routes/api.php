<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TimesheetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class,'login']);
// Route::post('/register', [AuthController::class,'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/show', [AuthController::class,'show']);
    Route::post('/logout', [AuthController::class,'logout']);

    Route::post('/savepublickey', [AuthController::class,'save_key']);
    Route::post('/verify', [AuthController::class,'verify_key']);

    Route::post('/create-timesheets', [TimesheetController::class,'store']);
    Route::get('/show_timesheets', [TimesheetController::class,'show']);
    Route::get('/timesheet_today', [TimesheetController::class,'show_today']);
});
