<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Cms\HomePageController;
use App\Http\Controllers\Admin\Cms\HeroSectionController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'pages.public.home')->name('home');
Route::view('/about-us', 'pages.public.about')->name('about');
Route::view('/courses', 'pages.public.courses')->name('courses');
Route::view('/courses/academic-writing', 'pages.public.course-detail')->name('courses.show');

Route::view('/free-test', 'pages.public.free-test')->name('free-test');
Route::view('/free-test/grammar', 'pages.public.free-test-runner')->name('free-test.show');
Route::view('/free-test/grammar/result', 'pages.public.free-test-result')->name('free-test.result');

Route::view('/news', 'pages.public.news')->name('news');
Route::view('/contact', 'pages.public.contact')->name('contact');


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::view('/login', 'pages.auth.login')->name('login');
Route::view('/register', 'pages.auth.register')->name('register');


/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::prefix('student')
    ->name('student.')
    ->group(function () {
        Route::view('/', 'pages.student.dashboard')->name('dashboard');

        Route::view('/my-courses', 'pages.student.my-courses')->name('my-courses');
        Route::view('/my-courses/toefl-preparation-mastery', 'pages.student.learning-path')->name('learning-path');

        Route::view('/my-courses/toefl-preparation-mastery/module-01', 'pages.student.module-material')->name('module-material');
        Route::view('/my-courses/toefl-preparation-mastery/module-01/practice', 'pages.student.module-practice')->name('module-practice');
        Route::view('/my-courses/toefl-preparation-mastery/module-01/completed', 'pages.student.module-completed')->name('module-completed');

        Route::view('/my-courses/toefl-preparation-mastery/final-exam', 'pages.student.final-exam')->name('final-exam');
        Route::view('/my-courses/toefl-preparation-mastery/final-exam/result', 'pages.student.final-exam-result')->name('final-exam-result');

        Route::view('/all-courses', 'pages.student.all-courses')->name('all-courses');
        Route::view('/testimoni', 'pages.student.testimoni')->name('testimoni');
        Route::view('/profile', 'pages.student.profile')->name('profile');
    });


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/', 'pages.admin.dashboard')->name('dashboard');

        Route::view('/orders', 'pages.admin.orders.index')->name('orders.index');
        Route::view('/students', 'pages.admin.students.index')->name('students.index');

        Route::prefix('cms')
            ->name('cms.')
            ->group(function () {
                Route::get('/home', [HomePageController::class, 'index'])->name('home.index');

                Route::resource('hero-sections', HeroSectionController::class)
                    ->only(['index']);
            });
    });
