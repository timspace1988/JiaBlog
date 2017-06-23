<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Blog pages
Route::get('/', function () {
    return redirect('/blog');
});

Route::get('/blog', 'BlogController@index')->name('blog.index');
Route::get('/blog/{slug}', 'BlogController@showPost')->name('blog.show');

//Admin area
Route::get('/admin', function(){
    return redirect('/admin/post');
});

Route::group([
    'namespace' => 'Admin',
    'middleware' => 'auth',
], function(){
    Route::resource('/admin/post', 'PostController');
    Route::resource('/admin/tag', 'TagController', ['except' => 'show']);
    Route::get('/admin/upload', 'UploadController@index')->name('admin.upload.index');
    Route::post('/admin/upload/file', 'UploadController@uploadFile')->name('admin.upload.file');
    Route::delete('/admin/upload/file', 'UploadController@deleteFile')->name('admin.delete.file');
    Route::post('/admin/upload/folder', 'UploadController@createFolder')->name('admin.create.folder');
    Route::delete('/admin/upload/folder', 'UploadController@deleteFolder')->name('admin.delete.folder');
});

//log in and log out
Route::get('/auth/login', 'Auth\AuthController@getLogin')->name('login');
Route::post('/auth/login', 'Auth\AuthController@postLogin')->name('login');
Route::get('/auth/logout', 'Auth\AuthController@getLogout')->name('logout');
