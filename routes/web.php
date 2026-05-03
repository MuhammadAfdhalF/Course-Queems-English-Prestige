<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Cms\HomePageController;
use App\Http\Controllers\Admin\Cms\HeroSectionController;
use App\Http\Controllers\Admin\Cms\FaqController;
use App\Http\Controllers\Admin\Cms\WhyChooseUsController;
use App\Http\Controllers\Admin\Cms\AboutPageController;
use App\Http\Controllers\Admin\Cms\VisionsMissionController;
use App\Http\Controllers\Admin\Cms\AboutUsController;
use App\Http\Controllers\Admin\Cms\ProfileVideoController;
use App\Http\Controllers\Admin\Cms\MentorController;
use App\Http\Controllers\Admin\Cms\ContactPageController;
use App\Http\Controllers\Admin\Cms\InformationPostController;
use App\Http\Controllers\Admin\Cms\FreeTestController;
use App\Http\Controllers\Admin\Cms\FreeTestCategoryController;

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
                Route::get('/about', [AboutPageController::class, 'index'])->name('about.index');

                Route::resource('hero-sections', HeroSectionController::class)
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::resource('faqs', FaqController::class)
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::resource('why-choose-us', WhyChooseUsController::class)
                    ->parameters([
                        'why-choose-us' => 'whyChooseUs',
                    ])
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::get('/vision-mission', [VisionsMissionController::class, 'index'])
                    ->name('vision-mission.index');

                Route::post('/vision-mission', [VisionsMissionController::class, 'save'])
                    ->name('vision-mission.save');

                Route::get('/about-us', [AboutUsController::class, 'index'])
                    ->name('about-us.index');

                Route::post('/about-us', [AboutUsController::class, 'save'])
                    ->name('about-us.save');

                Route::resource('profile-videos', ProfileVideoController::class)
                    ->parameters([
                        'profile-videos' => 'profileVideo',
                    ])
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::resource('mentors', MentorController::class)
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::get('/contact', [ContactPageController::class, 'index'])
                    ->name('contact.index');

                Route::post('/contact', [ContactPageController::class, 'save'])
                    ->name('contact.save');

                Route::post('/contact/social-links', [ContactPageController::class, 'storeSocialLink'])
                    ->name('contact.social-links.store');

                Route::put('/contact/social-links/{contactSocialLink}', [ContactPageController::class, 'updateSocialLink'])
                    ->name('contact.social-links.update');

                Route::delete('/contact/social-links/{contactSocialLink}', [ContactPageController::class, 'destroySocialLink'])
                    ->name('contact.social-links.destroy');

                Route::resource('news-gallery', InformationPostController::class)
                    ->parameters([
                        'news-gallery' => 'informationPost',
                    ])
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::post('/news-gallery/{informationPost}/images', [InformationPostController::class, 'storeImage'])
                    ->name('news-gallery.images.store');

                Route::delete('/news-gallery/images/{informationPostImage}', [InformationPostController::class, 'destroyImage'])
                    ->name('news-gallery.images.destroy');

                Route::resource('free-tests', FreeTestController::class)
                    ->parameters([
                        'free-tests' => 'freeTest',
                    ])
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::resource('free-test-categories', FreeTestCategoryController::class)
                    ->parameters([
                        'free-test-categories' => 'freeTestCategory',
                    ])
                    ->only(['index', 'store', 'update', 'destroy']);
            });
    });
