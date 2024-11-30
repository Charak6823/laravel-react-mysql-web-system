<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('roles',RoleController::class);
Route::apiResource('provinces',ProvinceController::class);
Route::apiResource('employees',EmployeeController::class);
Route::apiResource('suppliers',SupplierController::class);
Route::apiResource('unit_types',UnitTypeController::class);
Route::apiResource('customers',CustomerController::class);
Route::apiResource('payment_methods', PaymentMethodController::class);
Route::apiResource('brands', BrandController::class);
Route::post(uri:'brands/{id}', action:[BrandController::class,'update']);
Route::apiResource('auth',AuthController::class);
Route::post('addresses',[AddressController::class,'index']);



