<?php

use App\Http\Controllers\ClassroomAndMemberController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// CLASS MEMBER__
Route::middleware(['auth', 'user'])->group(function () {
    Route::controller(ClassroomController::class)->as('classroom.')->group(function () {
        Route::get('beranda', 'index')->name('index');
        Route::post('store-classroom', 'store')->name('store');
        Route::get('mata-pelajaran/{id}', 'show')->name('show');
        Route::post('mata-pelajaran/{id}', 'update')->name('update');
    });

    Route::controller(ClassroomAndMemberController::class)->as('classroom_member.')->group( function () {
        Route::post('enroll-classroom', 'store')->name('enroll');
    });

    Route::controller(ExamController::class)->as('exam.')->group(function () {
        Route::get('mata-pelajaran/{classroom}/ujian/{exam}', 'show')->name('show');
        Route::post('mata-pelajaran/{classroom_id}/ujian-baru', 'store')->name('store');
        Route::put('mata-pelajaran/{classroom}/ujian/{exam}',  'update')->name('update');
    });

    Route::controller(QuestionController::class)->as('question.')->group(function () {
        Route::post('mata-pelajaran/{classroom}/ujian/{exam}', 'store')->name('store');
        Route::post('mata-pelajaran/{classroom}/ujian/{exam}/upload', 'upload')->name('upload');
        Route::put('mata-pelajaran/{classroom}/ujian/{exam}/edit-question', 'update')->name('update');
    });
    // Route::get('/classroom/{classroom_id}/exam-create', [ExamController::class, 'create']);

    
});

// ADMIN__
Route::middleware(['auth', 'admin'])->as('admin.')->prefix('admin')->group(function () {
    Route::get('dashboard', function() {
        return view('welcome');
    })->name('dashboard');
});



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route for CRUD classroom
// Route::resource('/classroom', ClassroomController::class);

// Route for see member of classroom
Route::get('/classroom/{classroom_id}/members', [ClassroomAndMemberController::class, 'index']);

// Route for create new exam
// Route::get('/classroom/{classroom_id}/exam-create', [ExamController::class, 'create']);
// Route::post('/classroom/{classroom_id}/exam-create', [ExamController::class, 'store']);

// Route for show all exam of the classroom
// Route::get('/classroom/{classroom_id}/exam-show-all', [ExamController::class, 'index']);

// Route for show one exam by exam id
// Route::get('/classroom/{classroom_id}/exam/{exam_id}', [ExamController::class, 'show']);

// Route for updating exam information
// Route::get('/classroom/{classroom_id}/exam-update/{exam_id}', [ExamController::class, 'edit']);
// Route::put('/classroom/{classroom_id}/exam-update/{exam_id}', [ExamController::class, 'update']);

// Route for delete exam entity
Route::delete('/classroom/{classroom_id}/exam-delete/{exam_id}', [ExamController::class, 'destroy']);

// Route for open or close exam
Route::post('/classroom/{classroom_id}/exam-change-status/{exam_id}', [ExamController::class, 'changeStatus']);

// Route for show question (all and by question id)
Route::get('/classroom/{classroom_id}/exam-show-question_by_id/{question_id}', [QuestionController::class, 'show']);
Route::get('/classroom/{classroom_id}/exam-show-all-question/{exam_id}', [QuestionController::class, 'index']);
Route::get('/classroom/{classroom_id}/start-exam/{exam_id}', [QuestionController::class, 'startExam']);

// // Route for add question of the exam
// Route::get('/classroom/{classroom_id}/exam-add-question/{exam_id}', [QuestionController::class, 'create']);
// Route::post('/classroom/{classroom_id}/exam-add-question/{exam_id}', [QuestionController::class, 'store']);

// Route for update question of the exam
Route::get('/classroom/{classroom_id}/exam-edit-question/{question_id}', [QuestionController::class, 'edit']);
Route::put('/classroom/{classroom_id}/exam-edit-question/{question_id}', [QuestionController::class, 'update']);

// Route for delete exam question
Route::get('/classroom/{classroom_id}/exam-delete-question/{question_id}', [QuestionController::class, 'destroy']);

// Route::resource('/question', QuestionController::class);
Route::resource('/member', ClassroomAndMemberController::class);

Route::fallback(function () {
    return view('welcome');
});