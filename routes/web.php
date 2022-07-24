<?php

use App\Http\Controllers\ClassroomAndMemberController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('/classroom', ClassroomController::class);
Route::get('/classroom/{id}/exam-create', [ExamController::class, 'create']);
Route::get('/classroom/{id}/exams', [ExamController::class, 'show']);
Route::post('/classroom/exam-create', [ExamController::class, 'store']);
Route::get('/classroom/exam-update/{id}', [ExamController::class, 'edit']);
Route::put('/classroom/exam-update/{id}', [ExamController::class, 'update']);
Route::delete('/classroom/{classroom_id}/exam-delete/{exam_id}', [ExamController::class, 'destroy']);

Route::get('/classroom/{classroom_id}/exam-show-question/{exam_id}/question/{question_id}', [QuestionController::class, 'show']);
Route::get('/classroom/{classroom_id}/exam-show-all-question/{exam_id}', [QuestionController::class, 'index']);
Route::get('/classroom/{classroom_id}/exam-add-question/{exam_id}', [QuestionController::class, 'create']);
Route::post('/classroom/{classroom_id}/exam-add-question/{exam_id}', [QuestionController::class, 'store']);
Route::get('/classroom/{classroom_id}/exam-edit-question/{question_id}', [QuestionController::class, 'edit']);
Route::put('/classroom/{classroom_id}/exam-edit-question/{question_id}', [QuestionController::class, 'update']);
Route::delete('/classroom/{classroom_id}/exam-delete-question/{question_id}', [QuestionController::class, 'destroy']);

Route::resource('/question', QuestionController::class);
Route::resource('/member', ClassroomAndMemberController::class);