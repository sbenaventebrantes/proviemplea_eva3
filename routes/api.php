<?php

use App\Http\Controllers\Api\AdminContactController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\PersonController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

Route::prefix('persons')->group(function () {
    Route::get('/', [PersonController::class, 'index']);
    Route::post('/', [PersonController::class, 'store']);
    Route::get('/{person}', [PersonController::class, 'show']);
    Route::patch('/{person}', [PersonController::class, 'update']);
    Route::delete('/{person}', [PersonController::class, 'destroy']);
});

Route::prefix('companies')->group(function () {
    Route::get('/', [CompanyController::class, 'index']);
    Route::post('/', [CompanyController::class, 'store']);
    Route::get('/{company}', [CompanyController::class, 'show']);
    Route::patch('/{company}', [CompanyController::class, 'update']);
    Route::delete('/{company}', [CompanyController::class, 'destroy']);
});

Route::prefix('admin')->group(function () {
    Route::get('/contacts', [AdminContactController::class, 'index']);
    Route::post('/contacts', [AdminContactController::class, 'store']);
    Route::patch('/contacts/{contact}/status', [AdminContactController::class, 'updateStatus']);
    Route::get('/statistics', [AdminContactController::class, 'statistics']);
});

Route::get('/documentation', function () {
    return response()->file(resource_path('views/swagger.html'));
});

Route::get('/swagger.yaml', function () {
    return response()->file(base_path('swagger.yaml'), [
        'Content-Type' => 'application/yaml; charset=UTF-8',
    ]);
});
