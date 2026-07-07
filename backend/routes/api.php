<?php

use App\Http\Controllers\Api\V1\AboutContentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ContactMessageController;
use App\Http\Controllers\Api\V1\EquipmentCategoryController;
use App\Http\Controllers\Api\V1\EquipmentController;
use App\Http\Controllers\Api\V1\LaboratoryController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\ServiceRequestController;
use App\Http\Controllers\Api\V1\SiteSettingController;
use App\Http\Controllers\Api\V1\TrainingCourseController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(SetLocale::class)->group(function () {
    // Public, read-only content
    Route::get('laboratories', [LaboratoryController::class, 'index']);
    Route::get('laboratories/{code}', [LaboratoryController::class, 'show']);

    Route::get('equipment-categories', [EquipmentCategoryController::class, 'index']);
    Route::get('equipment', [EquipmentController::class, 'index']);
    Route::get('equipment/{code}', [EquipmentController::class, 'show']);

    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{slug}', [ServiceController::class, 'show']);

    Route::get('training-courses', [TrainingCourseController::class, 'index']);
    Route::get('training-courses/{id}', [TrainingCourseController::class, 'show']);

    Route::get('news', [NewsController::class, 'index']);
    Route::get('news/{slug}', [NewsController::class, 'show']);

    Route::get('settings', [SiteSettingController::class, 'show']);
    Route::get('about', [AboutContentController::class, 'show']);

    // Public form submissions
    Route::post('contact', [ContactMessageController::class, 'store']);
    Route::post('service-requests', [ServiceRequestController::class, 'store']);

    // Auth
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
    });
});
