<?php

use App\Http\Controllers\ComentariosController;
use App\Http\Controllers\CurtidaController;
use App\Http\Controllers\JogoController;
use App\Http\Controllers\usuarioController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('teste');
})->name('home');

Route::get('/example', function () {
    return view('welcome');
})->name('example');

Route::get('/perfil', [usuarioController::class, 'index'])->name('perfil');
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jogos/create', [JogoController::class, 'create'])->name('jogos.create');
Route::post('/jogos/create', [JogoController::class, 'store'])->middleware('auth');


Route::get('/buscar-jogos', [JogoController::class, 'buscarJogos'])->name('jogos.buscar');
Route::get('/todos-os-resultados', [JogoController::class, 'mostrarTodosResultados']);
Route::get('/jogos/{id}', [JogoController::class, 'show'])->name('jogos.show');
Route::post('/jogos/{id}/status', [JogoController::class, 'atualizarStatus'])->name('jogos.atualizar-status')->middleware('auth');


Route::post('/curtir/{idJogo}', [CurtidaController::class, 'curtir'])->name('jogo.curtir');
Route::post('/jogo/{idJogo}/comentario', [ComentariosController::class, 'store'])->name('comentarios.store');
Route::post('/perfil/upload-avatar', [UsuarioController::class, 'uploadAvatar'])->name('perfil.uploadAvatar');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
