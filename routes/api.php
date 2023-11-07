<?php

use App\Http\Controllers\Api\Auth\LoginJwtController;
// use App\Http\Controllers\Api\Acesso\UsuarioController;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Tarefas\TagsController;
use App\Http\Controllers\Tarefas\TarefasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function(){

    // ROTAS DE LOGIN
    Route::post('/login', [LoginJwtController::class, 'login'])->name('login');
    Route::get('/logout', [LoginJwtController::class, 'logout'])->name('logout');
    Route::get('/refresh', [LoginJwtController::class, 'refresh'])->name('refresh');
    Route::post('/usuarios/login', [UsuarioController::class, 'consultar'])->name('consultar');
    Route::post('/users', [UserController::class, 'store']);

    
    Route::group(['middleware' => ['jwt.auth']], function(){

        // ROTAS DO USUÁRIO ANTIGA
        Route::post('/users/login', [UserController::class, 'consultar'])->name('consultar');
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        
    
        // ROTAS DO USUARIO
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
        Route::post('/usuarios', [UsuarioController::class, 'adicionar']);
        Route::put('/usuarios/{id}', [UsuarioController::class, 'alterar']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'excluir']);    

        // ROTAS DE TAREFAS
        Route::get('/tarefas', [TarefasController::class, 'consultar'])->name('consultar');
        Route::get('/tarefas/{id}', [TarefasController::class, 'show']);
        Route::post('/tarefas', [TarefasController::class, 'adicionar']);
        Route::put('/tarefas/{id}', [TarefasController::class, 'alterar']);
        Route::delete('/tarefas/{id}', [TarefasController::class, 'excluir']);   
        Route::post('/tarefas-consulta', [TarefasController::class, 'consultarTarefas']);

        // ROTAS DE TAGS
        Route::get('/tags', [TagsController::class, 'consultar'])->name('consultar');
        Route::get('/tags/{id}', [TagsController::class, 'show']);
        Route::post('/tags', [TagsController::class, 'adicionar']);
        Route::put('/tags/{id}', [TagsController::class, 'alterar']);
        Route::delete('/tags/{id}', [TagsController::class, 'excluir']);   
    });
});