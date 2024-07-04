<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/students', 'App\Http\Controllers\StudentController@index');
Route::post('/students', 'App\Http\Controllers\StudentController@store');
Route::get('/students/{id}', 'App\Http\Controllers\StudentController@show');
Route::patch('/students/{id}', 'App\Http\Controllers\StudentController@update');
