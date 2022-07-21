<?php

use App\Http\Controllers\CouponController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

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


Route::group(['prefix' => 'coupons'], function () {
    Route::get('/', [CouponController::class, 'index']);
    Route::post('/', [CouponController::class, 'store']);
    Route::get('/{id}', [CouponController::class, 'read']);
    Route::put('/{id}', [CouponController::class, 'update']);
    Route::delete('/{id}', [CouponController::class, 'delete']);
});

Route::group(['prefix' => 'courses'], function () {
    Route::get('/', [CourseController::class, 'courseList']);
});
Route::get('course-categories', [CourseController::class, 'categoryList']);
Route::get('get-all-coupons', [CouponController::class, 'couponList']);
Route::post('apply-coupon', [CourseController::class, 'applyCoupon']);
Route::get('get-all-applied-coupons', [CourseController::class, 'getAllAppliedCouponList']);
Route::get('where-apply-code/{code}',[CouponController::class,'getWhereApplyCouponCode']);


