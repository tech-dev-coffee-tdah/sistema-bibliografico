<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\RelatorioController;

// Redirecionamento da raiz
Route::redirect('/', '/livros');

// Recursos principais (CRUDs)
Route::resource('livros', LivroController::class);
Route::resource('autores', AutorController::class);
Route::resource('assuntos', AssuntoController::class);

// Rota oficial do Relatório PDF
Route::get('/relatorio/pdf', [RelatorioController::class, 'gerarPdf'])->name('relatorio.pdf');
