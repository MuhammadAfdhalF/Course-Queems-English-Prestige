<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.public.home')->name('home');
Route::view('/login', 'pages.auth.login')->name('login');
Route::view('/student', 'pages.student.dashboard')->name('student.dashboard');
Route::view('/admin', 'pages.admin.dashboard')->name('admin.dashboard');
