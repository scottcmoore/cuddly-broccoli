<?php

use Illuminate\Http\Request;

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

// Confirm the API is up and running:
Route::get('/', function() {
    return response("", 200);
});

// Register routes for products
Route::get('/products', 'ProductsController@index');
Route::get('/products/{product}', 'ProductsController@show');