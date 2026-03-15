<?php

use Illuminate\Support\Facades\Route;
use VertexSolutions\Academy\Http\Controllers\CertificateController;
use VertexSolutions\Academy\Http\Controllers\MemberPanel\CatalogController;
use VertexSolutions\Academy\Http\Controllers\MemberPanel\CourseController;
use VertexSolutions\Academy\Http\Controllers\MemberPanel\EnrollmentController;
use VertexSolutions\Academy\Http\Controllers\MemberPanel\LessonPlayerController;

Route::get('/', [CatalogController::class, 'catalog'])->name('catalog');
Route::get('catalog', [CatalogController::class, 'catalog'])->name('catalog');

Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::post('courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('enroll');

Route::get('enrollments/{enrollment}/lesson/{lesson}', [LessonPlayerController::class, 'player'])->name('player');
Route::post('enrollments/{enrollment}/lessons/{lesson}/complete', [LessonPlayerController::class, 'completeLesson'])->name('lessons.complete');

Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
