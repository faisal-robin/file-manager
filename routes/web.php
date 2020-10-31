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
    return view('auth/login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    // Authentication Routes...
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('file-manager', 'FileManagerController');
    Route::post('file-upload', 'FileManagerController@file_upload');
    Route::get('file-delete/{any}', 'FileManagerController@file_delete');
    Route::get('file-download/{any}', 'FileManagerController@file_download');
    Route::get('folder-delete/{any}', 'FileManagerController@folder_delete');
    Route::resource('extension', 'ExtensionController');
    Route::resource('file-size', 'FileSizeController');
});
