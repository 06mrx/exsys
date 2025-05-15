<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamController;



Route::post('/exams/save-answer', [App\Http\Controllers\ExamController::class, 'saveAnswer']);



require __DIR__.'/auth.php';