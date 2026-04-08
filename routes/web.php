<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\RelatoriosController;
use App\Http\Controllers\ConfiguracoesController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'form'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.session')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/vendas-semanais', [DashboardController::class, 'get_vendas_semanais']);

    Route::prefix('pedidos')->group(function () {
        Route::get('/', [PedidosController::class, 'index'])->name('pedidos');
        Route::get('/get-pedidos', [PedidosController::class, 'getPedidos'])->name('pedidos.get');
        Route::post('/salvar', [PedidosController::class, 'salvar']);
        Route::get('/detalhes/{id}', [PedidosController::class, 'detalhes']);
        Route::post('/atualizar/{id}', [PedidosController::class, 'atualizar']);
        Route::delete('/excluir/{id}', [PedidosController::class, 'excluir']);
    });

    Route::prefix('produtos')->group(function () {
        Route::get('/', [ProdutosController::class, 'index'])->name('produtos');
        Route::get('/get-produtos', [ProdutosController::class, 'getProdutos'])->name('produtos.get');
    });

    Route::prefix('clientes')->group(function () {
        Route::get('/', [ClientesController::class, 'index'])->name('clientes');
        Route::get('/get-clientes', [ClientesController::class, 'getClientes'])->name('clientes.get');
    });
    Route::prefix('relatorios')->group(function () {
        Route::get('/', [RelatoriosController::class, 'index'])->name('relatorios');
    });
    Route::prefix('configuracoes')->group(function () {
        Route::get('/', [ConfiguracoesController::class, 'index'])->name('configuracoes');
    });
});
