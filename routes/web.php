<?php

use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('items.list');
});

Route::group(['prefix' => 'employees'], function(){
    Route::get('/login-form', 'EmployeeController@index')->name('employees.login-form');
    Route::post('/login', 'EmployeeController@login')->name('employees.login');
});

Route::group(['prefix' => 'items'], function(){
    Route::get('/list', 'ItemController@index')->name('items.list');
    Route::get('/register-form', 'ItemController@create')->name('items.register-form');
    Route::get('/excel-form', 'ItemController@createExcel')->name('items.excel-form');
    Route::get('/excel-format', 'ItemController@ExcelFormatDownload')->name('items.excel-format');
    Route::get('/{type}/export-all', 'ItemController@exportAllItems')->name('items.export-all');
    Route::post('/register', 'ItemController@store')->name('items.register');
    Route::post('/excel-register', 'ItemController@excelImport')->name('items.excel-register');
    Route::get('/search', 'ItemController@search')->name('items.search');
    Route::get('/search-export', 'ItemController@exportSearchItems')->name('items.search-export');
    Route::get('/suggestions', 'ItemController@autoComplete')->name('items.suggestions');

    Route::group(['prefix' => '/{id}'], function(){
        Route::get('/active', 'ItemController@itemActive')->name('items.active');
        Route::get('/inactive', 'ItemController@itemInactive')->name('items.inactive');
        Route::get('/detail', 'ItemController@show')->name('items.detail');
        Route::get('/update', 'ItemController@show')->name('items.update');
        Route::patch('/update-data', 'ItemController@update')->name('items.update-data');
    });
    Route::delete('/delete', 'ItemController@destroy')->name('items.delete');
});

Route::group(['prefix' => 'categories'], function(){
    Route::get('/list', 'CategoryController@index')->name('categories.list');  
    Route::get('/deletable', 'CategoryController@getDeletableCategories')->name('categories.deletable');
    Route::post('/register', 'CategoryController@store')->name('categories.register');
    Route::post('/delete', 'CategoryController@destroy')->name('categories.delete');
});

