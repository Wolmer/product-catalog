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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Product
// Route::middleware('auth:api')->get('/products', 'ProductController@index');


Route::get('/products', 'ProductController@index');
Route::get('/categories', 'ProductController@getCategories');
Route::post('/products/create', 'ProductController@store');
Route::post('/products/update/{id}', 'ProductController@update');
Route::delete('/products/delete/{id}', 'ProductController@delete');
