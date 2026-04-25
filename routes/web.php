<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.public.home')->name('home');
Route::view('/about-us', 'pages.public.about')->name('about');
Route::view('/courses', 'pages.public.courses')->name('courses');
Route::view('/courses/academic-writing', 'pages.public.course-detail')->name('courses.show');

Route::view('/free-test', 'pages.public.free-test')->name('free-test');
Route::view('/free-test/grammar', 'pages.public.free-test-runner')->name('free-test.show');
Route::view('/free-test/grammar/result', 'pages.public.free-test-result')->name('free-test.result');

Route::view('/news', 'pages.public.news')->name('news');
Route::view('/contact', 'pages.public.contact')->name('contact');

Route::view('/login', 'pages.auth.login')->name('login');
Route::view('/student', 'pages.student.dashboard')->name('student.dashboard');
Route::view('/admin', 'pages.admin.dashboard')->name('admin.dashboard');
