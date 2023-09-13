<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\CartController;
use App\Http\Controllers\API\v1\CategoriesController;
use App\Http\Controllers\API\v1\PermissionController;
use App\Http\Controllers\API\v1\ProductController;
use App\Http\Controllers\API\v1\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::controller(AuthController::class)->group(function () {
  Route::post('login', 'login');
  Route::post('register', 'register');
  Route::post('logout', 'logout');
  Route::post('refresh', 'refresh');
});

Route::controller(UserController::class)->group(function () {
  Route::get('users', 'index');
  Route::get('users/{id}', 'show');
  Route::post('users', 'store');
  Route::put('users/{id}', 'update');
  Route::delete('users/{id}', 'destroy');
});

Route::controller(PermissionController::class)->group(function () {
  Route::post('assign-permission-to-role', 'assignPermissionToRole');
  Route::post('assign-permission-to-user', 'assignPermissionToUser');
});

Route::controller(CategoriesController::class)->group(function () {
  Route::get('categories', 'index');
  Route::get('categories/{id}', 'show');
  Route::post('categories', 'store');
  Route::put('categories/{id}', 'update');
  Route::delete('categories/{id}', 'destroy');
  Route::get('/categories/{categoryName}/products', CategoriesController::class . '@getProductsByCategory');
});

Route::controller(ProductController::class)->group(function () {
  Route::get('products', 'index');
  Route::get('products/{id}', 'show');
  Route::post('products', 'store');
  Route::put('products/{id}', 'update');
  Route::delete('products/{id}', 'destroy');
  Route::get('/products/category/{categoryName}', ProductController::class . '@getByCategory');
});

Route::controller(CartController::class)->group(function () {
  Route::get('carts', 'index');
  Route::post('carts', 'store');
  Route::get('carts/{id}', 'show');
  Route::put('carts/{id}', 'update');
  Route::delete('carts/{id}', 'destroy');
  // untuk simulasi checkout
  Route::post('carts/{id}/checkout', 'checkout');
  // untuk simulasi cancel
  Route::post('carts/{id}/cancel', 'cancel');
});
