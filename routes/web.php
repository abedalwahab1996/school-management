<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('students.index');
});

Route::get('/students/pdf', [StudentController::class, 'pdf'])->name('students.pdf');

Route::resource('teachers', TeacherController::class);
Route::resource('students', StudentController::class);
Route::resource('subjects', SubjectController::class);
