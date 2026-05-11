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
use App\Http\Controllers\Admin\Cms\FreeTestQuestionController;
use App\Http\Controllers\Admin\CourseManagement\CourseProgramController;
use App\Http\Controllers\Admin\RichTextUploadController;
use App\Http\Controllers\Admin\CourseManagement\CourseLevelController;
use App\Http\Controllers\Admin\CourseManagement\ModuleController;
use App\Http\Controllers\Admin\CourseManagement\ModuleMaterialController;
use App\Http\Controllers\Admin\CourseManagement\ModulePracticeController;
use App\Http\Controllers\Admin\CourseManagement\ModulePracticeQuestionController;
use App\Http\Controllers\Admin\CourseManagement\FinalExamController;
use App\Http\Controllers\Admin\CourseManagement\FinalExamQuestionController;
use App\Http\Controllers\Public\CourseController as PublicCourseController;
use App\Http\Controllers\Public\ContactController as PublicContactController;
use App\Http\Controllers\Public\AboutController as PublicAboutController;
use App\Http\Controllers\Public\InformationController as PublicInformationController;
use App\Http\Controllers\Public\HomeController as PublicHomeController;
use App\Http\Controllers\Public\FreeTestController as PublicFreeTestController;
use App\Http\Controllers\Admin\Cms\FreeTestResultController;
use App\Http\Controllers\Auth\AuthController;

// sementara logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicHomeController::class, 'index'])->name('home');
Route::get('/about-us', [PublicAboutController::class, 'index'])->name('about');

Route::get('/courses', [PublicCourseController::class, 'index'])->name('courses');
Route::get('/courses/{courseLevel:slug}', [PublicCourseController::class, 'show'])->name('courses.show');

Route::get('/free-test', [PublicFreeTestController::class, 'index'])->name('free-test');
Route::get('/free-test/{freeTest}', [PublicFreeTestController::class, 'show'])->name('free-test.show');
Route::post('/free-test/{freeTest}/submit', [PublicFreeTestController::class, 'submit'])->name('free-test.submit');
Route::get('/free-test/result/{freeTestResult}', [PublicFreeTestController::class, 'result'])->name('free-test.result');

Route::get('/news', [PublicInformationController::class, 'index'])->name('news');
Route::get('/news/{informationPost:slug}', [PublicInformationController::class, 'show'])->name('news.show');

Route::get('/contact', [PublicContactController::class, 'index'])->name('contact');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
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
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/', 'pages.admin.dashboard')->name('dashboard');


        Route::post('/rich-text/upload-image', [RichTextUploadController::class, 'uploadImage'])
            ->name('rich-text.upload-image');

        Route::prefix('course-management')
            ->name('course-management.')
            ->group(function () {

                // Course Programs
                Route::resource('programs', CourseProgramController::class)
                    ->parameters([
                        'programs' => 'courseProgram',
                    ])
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::get('/programs/{courseProgram}/levels', [CourseLevelController::class, 'index'])
                    ->name('programs.levels.index');

                Route::get('/programs/{courseProgram}/levels/create', [CourseLevelController::class, 'create'])
                    ->name('programs.levels.create');

                Route::post('/programs/{courseProgram}/levels', [CourseLevelController::class, 'store'])
                    ->name('programs.levels.store');

                // Course Levels
                Route::get('/levels/{courseLevel}/edit', [CourseLevelController::class, 'edit'])
                    ->name('levels.edit');

                Route::put('/levels/{courseLevel}', [CourseLevelController::class, 'update'])
                    ->name('levels.update');

                Route::delete('/levels/{courseLevel}', [CourseLevelController::class, 'destroy'])
                    ->name('levels.destroy');

                // Modules
                Route::get('/levels/{courseLevel}/modules', [ModuleController::class, 'index'])
                    ->name('levels.modules.index');

                Route::post('/levels/{courseLevel}/modules', [ModuleController::class, 'store'])
                    ->name('levels.modules.store');

                Route::put('/modules/{module}', [ModuleController::class, 'update'])
                    ->name('modules.update');

                Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])
                    ->name('modules.destroy');

                // Module Materials
                Route::get('/modules/{module}/materials', [ModuleMaterialController::class, 'index'])
                    ->name('modules.materials.index');

                Route::get('/modules/{module}/materials/preview', [ModuleMaterialController::class, 'preview'])
                    ->name('modules.materials.preview');

                Route::get('/modules/{module}/materials/create', [ModuleMaterialController::class, 'create'])
                    ->name('modules.materials.create');

                Route::post('/modules/{module}/materials', [ModuleMaterialController::class, 'store'])
                    ->name('modules.materials.store');

                Route::get('/materials/{moduleMaterial}/edit', [ModuleMaterialController::class, 'edit'])
                    ->name('materials.edit');

                Route::put('/materials/{moduleMaterial}', [ModuleMaterialController::class, 'update'])
                    ->name('materials.update');

                Route::delete('/materials/{moduleMaterial}', [ModuleMaterialController::class, 'destroy'])
                    ->name('materials.destroy');

                // practices
                Route::get('/modules/{module}/practice', [ModulePracticeController::class, 'index'])
                    ->name('modules.practice.index');

                Route::get('/modules/{module}/practice/create', [ModulePracticeController::class, 'create'])
                    ->name('modules.practice.create');

                Route::post('/modules/{module}/practice', [ModulePracticeController::class, 'store'])
                    ->name('modules.practice.store');

                Route::get('/practices/{modulePractice}/edit', [ModulePracticeController::class, 'edit'])
                    ->name('practices.edit');

                Route::put('/practices/{modulePractice}', [ModulePracticeController::class, 'update'])
                    ->name('practices.update');

                // manage practice questions
                Route::get('/practices/{modulePractice}/questions', [ModulePracticeQuestionController::class, 'index'])
                    ->name('practices.questions.index');

                Route::get('/practices/{modulePractice}/questions/create', [ModulePracticeQuestionController::class, 'create'])
                    ->name('practices.questions.create');

                Route::post('/practices/{modulePractice}/questions', [ModulePracticeQuestionController::class, 'store'])
                    ->name('practices.questions.store');

                Route::get('/practice-questions/{modulePracticeQuestion}/edit', [ModulePracticeQuestionController::class, 'edit'])
                    ->name('practice-questions.edit');

                Route::put('/practice-questions/{modulePracticeQuestion}', [ModulePracticeQuestionController::class, 'update'])
                    ->name('practice-questions.update');

                Route::delete('/practice-questions/{modulePracticeQuestion}', [ModulePracticeQuestionController::class, 'destroy'])
                    ->name('practice-questions.destroy');

                Route::get('/practices/{modulePractice}/preview', [ModulePracticeQuestionController::class, 'preview'])
                    ->name('practices.preview');


                // final exam
                Route::get('/levels/{courseLevel}/final-exam', [FinalExamController::class, 'index'])
                    ->name('levels.final-exam.index');

                Route::get('/levels/{courseLevel}/final-exam/create', [FinalExamController::class, 'create'])
                    ->name('levels.final-exam.create');

                Route::post('/levels/{courseLevel}/final-exam', [FinalExamController::class, 'store'])
                    ->name('levels.final-exam.store');

                Route::get('/final-exams/{finalExam}/edit', [FinalExamController::class, 'edit'])
                    ->name('final-exams.edit');

                Route::put('/final-exams/{finalExam}', [FinalExamController::class, 'update'])
                    ->name('final-exams.update');


                // manage final exam questions
                Route::get('/final-exams/{finalExam}/questions', [FinalExamQuestionController::class, 'index'])
                    ->name('final-exams.questions.index');

                Route::get('/final-exams/{finalExam}/questions/create', [FinalExamQuestionController::class, 'create'])
                    ->name('final-exams.questions.create');

                Route::post('/final-exams/{finalExam}/questions', [FinalExamQuestionController::class, 'store'])
                    ->name('final-exams.questions.store');

                Route::get('/final-exam-questions/{finalExamQuestion}/edit', [FinalExamQuestionController::class, 'edit'])
                    ->name('final-exam-questions.edit');

                Route::put('/final-exam-questions/{finalExamQuestion}', [FinalExamQuestionController::class, 'update'])
                    ->name('final-exam-questions.update');

                Route::delete('/final-exam-questions/{finalExamQuestion}', [FinalExamQuestionController::class, 'destroy'])
                    ->name('final-exam-questions.destroy');

                Route::get('/final-exams/{finalExam}/preview', [FinalExamQuestionController::class, 'preview'])
                    ->name('final-exams.preview');
            });

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

                Route::get('/free-test-results', [FreeTestResultController::class, 'index'])
                    ->name('free-test-results.index');

                Route::resource('free-test-categories', FreeTestCategoryController::class)
                    ->parameters([
                        'free-test-categories' => 'freeTestCategory',
                    ])
                    ->only(['index', 'store', 'update', 'destroy']);

                Route::get('/free-tests/{freeTest}/questions', [FreeTestQuestionController::class, 'index'])
                    ->name('free-tests.questions.index');

                Route::post('/free-tests/{freeTest}/questions', [FreeTestQuestionController::class, 'store'])
                    ->name('free-tests.questions.store');

                Route::put('/free-tests/questions/{freeTestQuestion}', [FreeTestQuestionController::class, 'update'])
                    ->name('free-tests.questions.update');

                Route::delete('/free-tests/questions/{freeTestQuestion}', [FreeTestQuestionController::class, 'destroy'])
                    ->name('free-tests.questions.destroy');
            });
    });
