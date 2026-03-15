<?php
/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA.
 * Versão: vs.1.0.0
 */

use App\Http\Controllers\Auth\ForgotPasswordCpfController;
use App\Http\Controllers\Auth\LoginCpfController;
use Illuminate\Support\Facades\Route;
use VertexSolutions\Core\Http\Controllers\PublicBibleController;
use VertexSolutions\HomePage\Http\Controllers\HomePageController;

// Homepage e páginas institucionais (públicas)
Route::get('/', [HomePageController::class, 'index'])->name('home');
Route::get('/faq', [HomePageController::class, 'faq'])->name('faq');
Route::get('/sobre', [HomePageController::class, 'about'])->name('about');
Route::get('/precos', [HomePageController::class, 'pricing'])->name('pricing');
Route::get('/contato', [HomePageController::class, 'contact'])->name('contact');

// Legal (LGPD)
Route::get('/legal/privacidade', [HomePageController::class, 'privacy'])->name('legal.privacy');
Route::get('/legal/termos', [HomePageController::class, 'terms'])->name('legal.terms');
Route::get('/legal/cookies', [HomePageController::class, 'cookies'])->name('legal.cookies');

// Bíblia pública (Core) – rotas em /bible e em /biblia-online para compatibilidade
Route::get('/bible', [PublicBibleController::class, 'index'])->name('bible.public.index');
Route::get('/bible/search', [PublicBibleController::class, 'search'])->name('bible.public.search');
Route::get('/bible/{versionAbbr}', [PublicBibleController::class, 'read'])->name('bible.public.read');
Route::get('/bible/{versionAbbr}/{bookNumber}', [PublicBibleController::class, 'book'])->name('bible.public.book');
Route::get('/bible/{versionAbbr}/{bookNumber}/{chapterNumber}', [PublicBibleController::class, 'chapter'])->name('bible.public.chapter');

Route::get('/biblia-online', [PublicBibleController::class, 'index'])->name('bible.public.index.biblia');
Route::get('/biblia-online/buscar', [PublicBibleController::class, 'search']);
Route::get('/biblia-online/search', [PublicBibleController::class, 'search']);
Route::get('/biblia-online/versao/{versionAbbr}', [PublicBibleController::class, 'read']);
Route::get('/biblia-online/versao/{versionAbbr}/livro/{bookNumber}', [PublicBibleController::class, 'book']);
Route::get('/biblia-online/versao/{versionAbbr}/livro/{bookNumber}/capitulo/{chapterNumber}', [PublicBibleController::class, 'chapter']);

// Auth custom (CPF login e recuperação por CPF + data nascimento)
Route::post('/login/cpf', LoginCpfController::class)->name('login.cpf');
Route::post('/forgot-password/cpf', ForgotPasswordCpfController::class)->name('password.email.cpf');

// Modo dev: login automático para testes (Admin Demo, Pastor Demo, Aluno Demo)
if (config('app.debug') || app()->environment('local')) {
    Route::post('/dev-login', [\App\Http\Controllers\Auth\DevLoginController::class, 'store'])->name('dev-login.store');
}
