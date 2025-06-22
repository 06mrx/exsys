<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DashboardController;



Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('institutions', InstitutionController::class)->middleware('admin');
    Route::resource('users', UserController::class)->middleware('admin');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import')->middleware('admin');
    Route::resource('questions', QuestionController::class)->middleware('admin');
    Route::get('exams/results', [ExamController::class, 'results'])->name('exams.results')->middleware('admin');
    Route::get('exams-student', [ExamController::class, 'indexStudent'])->name('exams.student-index');
    Route::resource('exams', ExamController::class);
    Route::get('exams/{exam}/start', [ExamController::class, 'start'])->name('exams.start');
    Route::post('exams/{exam}/submit', [ExamController::class, 'submit'])->name('exams.submit');
    Route::post('/exams/save-answer', [App\Http\Controllers\ExamController::class, 'saveAnswer']);

    Route::get('exams/{exam}/results/{user}', [ExamController::class, 'resultDetail'])->name('exams.result-detail')->middleware('admin');
    
    Route::get('quizzes/results', [QuizController::class, 'results'])->name('quizzes.results');
    Route::resource('quizzes', QuizController::class);
    Route::get('quizzes-student', [QuizController::class, 'indexStudent'])->name('quizzes.student');
    Route::post('quizzes/save-answer', [QuizController::class, 'saveAnswer'])->name('quizzes.save-answer');
    Route::get('quizzes/{quiz}/start', [QuizController::class, 'start'])->name('quizzes.start');
    Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
});




require __DIR__.'/auth.php';
