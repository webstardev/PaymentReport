<?php
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
Route::get('income-key-in/{id}', 'IncomeKeyInController@get');

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('brand', 'BrandController@create');
    Route::post('brand/filter', 'BrandController@filter');
    Route::get('brand/{id}', 'BrandController@get');

    Route::get('income-key-in/{id}', 'IncomeKeyInController@get');
    Route::post('income-key-in', 'IncomeKeyInController@create');
    Route::post('income-key-in/filter', 'IncomeKeyInController@filter');
    Route::post('income-key-in/changestatus', 'IncomeKeyInController@changeStatus');

    Route::get('expenses-key-in/{id}', 'ExpensesKeyInController@get');
    Route::post('expenses-key-in', 'ExpensesKeyInController@create');
    Route::post('expenses-key-in/filter', 'ExpensesKeyInController@filter');

    Route::get('currency', 'CurrencyController@get');
    Route::get('expenses-type/{id}', "ExpensesTypeController@get");
    Route::post('expenses-type', "ExpensesTypeController@create");

    Route::get('payment-method/{id}', "PaymentMethodController@get");
    Route::post('payment-method', "PaymentMethodController@create");

    Route::get('/supplier/{id}', "SupplierController@get");
    Route::post('/supplier', "SupplierController@create");

    Route::get('category/{id}', "CategoryController@get");
    Route::post('category', "CategoryController@create");
    Route::get('category/{id}/sub-category', "CategoryController@getSubCategory");

    Route::get('/agent/{id}', "AgentController@get");
    Route::post('/agent', "AgentController@create");
    Route::post('/agent/filter', "AgentController@filter");

    Route::post('create-user', "UserController@createUser");
});
