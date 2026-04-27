<?php

use Illuminate\Support\Facades\Route;

// Public Routes
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
Route::view('/register', 'pages.auth.register')->name('register');


// Student Routes
Route::view('/student', 'pages.student.dashboard')->name('student.dashboard');

Route::view('/student/my-courses', 'pages.student.my-courses')->name('student.my-courses');
Route::view('/student/my-courses/toefl-preparation-mastery', 'pages.student.learning-path')->name('student.learning-path');
Route::view('/student/my-courses/toefl-preparation-mastery/module-01', 'pages.student.module-material')->name('student.module-material');
Route::view('/student/my-courses/toefl-preparation-mastery/module-01/practice', 'pages.student.module-practice')->name('student.module-practice');
Route::view('/student/my-courses/toefl-preparation-mastery/module-01/completed', 'pages.student.module-completed')->name('student.module-completed');
Route::view('/student/my-courses/toefl-preparation-mastery/final-exam', 'pages.student.final-exam')->name('student.final-exam');
Route::view('/student/my-courses/toefl-preparation-mastery/final-exam/result', 'pages.student.final-exam-result')->name('student.final-exam-result');

Route::view('/student/all-courses', 'pages.student.all-courses')->name('student.all-courses');
Route::view('/student/testimoni', 'pages.student.testimoni')->name('student.testimoni');
Route::view('/student/profile', 'pages.student.profile')->name('student.profile');


// Admin Routes
Route::view('/admin', 'pages.admin.dashboard')->name('admin.dashboard');
Route::view('/admin/orders', 'pages.admin.orders.index')->name('admin.orders.index');