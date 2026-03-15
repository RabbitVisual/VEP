<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\Ministry\Http\Controllers\MemberPanel\MinistryDashboardController;
use VertexSolutions\Ministry\Http\Controllers\MinistryController;
use VertexSolutions\Ministry\Http\Controllers\MinistryMaterialController;
use VertexSolutions\Ministry\Http\Controllers\MinistryMemberController;
use VertexSolutions\Ministry\Http\Controllers\MinistryScheduleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('ministries', MinistryController::class)->names('ministry');
    // Member dashboard: painel/ministries/{ministry}/dashboard is in MemberPanel/routes/member.php

    Route::prefix('ministries/{ministry}')->name('ministry.')->group(function () {
        Route::get('members', [MinistryMemberController::class, 'index'])->name('members.index');
        Route::post('members', [MinistryMemberController::class, 'store'])->name('members.store');
        Route::put('members/{member}', [MinistryMemberController::class, 'update'])->name('members.update');
        Route::delete('members/{member}', [MinistryMemberController::class, 'destroy'])->name('members.destroy');

        Route::get('schedules', [MinistryScheduleController::class, 'index'])->name('schedules.index');
        Route::get('schedules/create', [MinistryScheduleController::class, 'create'])->name('schedules.create');
        Route::post('schedules', [MinistryScheduleController::class, 'store'])->name('schedules.store');
        Route::get('schedules/{schedule}', [MinistryScheduleController::class, 'show'])->name('schedules.show');
        Route::get('schedules/{schedule}/edit', [MinistryScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('schedules/{schedule}', [MinistryScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('schedules/{schedule}', [MinistryScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::post('schedules/{schedule}/assign', [MinistryScheduleController::class, 'assign'])->name('schedules.assign');
        Route::put('schedules/{schedule}/assignments/{assignment}', [MinistryScheduleController::class, 'updateAssignment'])->name('schedules.assignments.update');
        Route::delete('schedules/{schedule}/assignments/{assignment}', [MinistryScheduleController::class, 'unassign'])->name('schedules.assignments.destroy');

        Route::get('materials', [MinistryMaterialController::class, 'index'])->name('materials.index');
        Route::get('materials/create', [MinistryMaterialController::class, 'create'])->name('materials.create');
        Route::post('materials', [MinistryMaterialController::class, 'store'])->name('materials.store');
        Route::get('materials/{material}', [MinistryMaterialController::class, 'show'])->name('materials.show');
        Route::get('materials/{material}/edit', [MinistryMaterialController::class, 'edit'])->name('materials.edit');
        Route::put('materials/{material}', [MinistryMaterialController::class, 'update'])->name('materials.update');
        Route::delete('materials/{material}', [MinistryMaterialController::class, 'destroy'])->name('materials.destroy');
    });
});
