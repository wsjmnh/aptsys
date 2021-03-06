<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix'=>'api'], function(){
 	Route::get('/posts', 'PostController@allPosts')->name('allPosts');
 	Route::get('/posts/{id}', 'PostController@getPost')->name('getPost');
 	Route::post('/posts', 'PostController@createPost')->name('createPost');
 	Route::delete('delete/posts/{id}', 'PostController@deletePost')->name('deletePost');
 	Route::get('/posts/{id}/comments', 'PostController@postComments')->name('getComments');
 	Route::post('/posts/{id}/comments', 'PostController@createPostComment')->name('createComments');
});