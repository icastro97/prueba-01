<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;



Route::resource('usuarios', UsuarioController::class);

Route::get('getContract/{id}', [UsuarioController::class,'getContract'])->name('get.contract');
Route::get('/firma/{path}', [UsuarioController::class, 'getFirma'])->name('usuarios.firma');

Route::get('/', function () {
    return redirect('/usuarios');
});
