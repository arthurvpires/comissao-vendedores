<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\SellerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::controller(SellerController::class)->group(function () {
        Route::post('/create/seller', 'store');
        Route::get('/sellers', 'index');
        Route::get('/seller/{id}/sales', 'salesBySeller');
    });

    Route::controller(SaleController::class)->group(function () {
        Route::post('/create/sale', 'store');
        Route::get('/sales', 'index');
    });

    Route::controller(EmailController::class)->group(function () {
        Route::post('/seller/resend-daily-report-email', 'resendDailyReportEmail');
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
