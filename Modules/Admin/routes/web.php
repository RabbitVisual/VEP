<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\Admin\Http\Controllers\AdminController;
use VertexSolutions\Admin\Http\Controllers\ProfileController;
use VertexSolutions\Admin\Http\Controllers\SensitiveFieldChangeRequestController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admins')->group(function () {
        Route::get('profile', [ProfileController::class, 'show'])->name('admin.profile.show');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::get('change-requests', [SensitiveFieldChangeRequestController::class, 'index'])->name('admin.change-requests.index');
        Route::post('change-requests/{change_request}/approve', [SensitiveFieldChangeRequestController::class, 'approve'])->name('admin.change-requests.approve');
        Route::post('change-requests/{change_request}/reject', [SensitiveFieldChangeRequestController::class, 'reject'])->name('admin.change-requests.reject');
    });
    Route::resource('admins', AdminController::class)->names('admin');
});
