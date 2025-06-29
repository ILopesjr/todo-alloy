<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('tasks')->group(function () {
    Route::apiResource('/', TaskController::class)->parameters(['' => 'task']);
    Route::patch('/{task}/toggle', [TaskController::class, 'toggle']);
});
