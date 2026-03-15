<?php

use Illuminate\Support\Facades\Route;
use VertexSolutions\Academy\Http\Controllers\Admin\CourseController;
use VertexSolutions\Academy\Http\Controllers\Admin\CourseModuleController;
use VertexSolutions\Academy\Http\Controllers\Admin\LessonController;

Route::resource('courses', CourseController::class)->names('courses');

Route::get('courses/{course}/modules/create', [CourseModuleController::class, 'create'])->name('courses.modules.create');
Route::post('courses/{course}/modules', [CourseModuleController::class, 'store'])->name('courses.modules.store');

Route::get('modules/{module}/edit', [CourseModuleController::class, 'edit'])->name('modules.edit');
Route::put('modules/{module}', [CourseModuleController::class, 'update'])->name('modules.update');
Route::delete('modules/{module}', [CourseModuleController::class, 'destroy'])->name('modules.destroy');

Route::get('modules/{module}/lessons/create', [LessonController::class, 'create'])->name('modules.lessons.create');
Route::post('modules/{module}/lessons', [LessonController::class, 'store'])->name('modules.lessons.store');

Route::get('lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
Route::put('lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
Route::delete('lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
