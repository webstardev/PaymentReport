<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Models\Brand;
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

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('brand', 'BrandController@create');
    Route::get('brand/{id}', 'BrandController@get');
    Route::post('keyin', 'KeyInController@store');
    Route::get('keyin/{id}', 'KeyInController@get');
    Route::post('keyin/filter', 'KeyInController@filter');
    Route::get('currency', 'CurrencyController@get');
    Route::get('expenses-type/{id}', "ExpensesTypeController@get");
    Route::post('expenses-type', "ExpensesTypeController@create");
    Route::get('payment-method/{id}', "PaymentMethodController@get");
    Route::post('payment-method', "PaymentMethodController@create");
    Route::post('create-user', "UserController@createUser");
});
