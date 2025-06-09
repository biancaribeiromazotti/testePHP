<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rotas de Clientes
Route::delete('clientes/destroy-multiple', [ClienteController::class, 'destroyMultiple'])
     ->name('clientes.destroy-multiple');
Route::resource('clientes', ClienteController::class);

// Rotas de Produtos
Route::delete('produtos/destroy-multiple', [ProdutoController::class, 'destroyMultiple'])
     ->name('produtos.destroy-multiple');
Route::resource('produtos', ProdutoController::class);

// Rotas de Pedidos
Route::delete('pedidos/destroy-multiple', [PedidoController::class, 'destroyMultiple'])
     ->name('pedidos.destroy-multiple');
Route::resource('pedidos', PedidoController::class);