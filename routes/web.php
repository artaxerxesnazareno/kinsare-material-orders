<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Rota inicial
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/painel', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil do usuário
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/sair', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Rotas para Solicitantes
    Route::middleware(['requester'])->group(function () {
        // Pedidos
        Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/pedidos/criar', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/pedidos', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/pedidos/{order}/enviar-revisao', [OrderController::class, 'sendToReview'])
            ->name('orders.send-to-review');
    });

    // Rotas para Aprovadores
    Route::middleware(['approver'])->group(function () {
        Route::get('/pedidos/pendentes', [OrderController::class, 'pending'])->name('orders.pending');
        Route::post('/pedidos/{order}/aprovar', [OrderController::class, 'approve'])->name('orders.approve');
        Route::post('/pedidos/{order}/rejeitar', [OrderController::class, 'reject'])->name('orders.reject');
        Route::post('/pedidos/{order}/solicitar-alteracoes', [OrderController::class, 'requestChanges'])
            ->name('orders.request-changes');
    });

    // Rotas para Administradores
    Route::middleware(['admin'])->group(function () {
        // Materiais
        Route::get('/materiais/criar', [MaterialController::class, 'create'])->name('materials.create');
        Route::post('/materiais', [MaterialController::class, 'store'])->name('materials.store');
        Route::get('/materiais/{material}/editar', [MaterialController::class, 'edit'])->name('materials.edit');
        Route::put('/materiais/{material}', [MaterialController::class, 'update'])->name('materials.update');
        Route::delete('/materiais/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
        Route::get('/materiais/relatorio', [MaterialController::class, 'report'])->name('materials.report');

        // Grupos
        Route::get('/grupos', [GroupController::class, 'index'])->name('groups.index');
        Route::get('/grupos/criar', [GroupController::class, 'create'])->name('groups.create');
        Route::post('/grupos', [GroupController::class, 'store'])->name('groups.store');
        Route::get('/grupos/{group}', [GroupController::class, 'show'])->name('groups.show');
        Route::get('/grupos/{group}/editar', [GroupController::class, 'edit'])->name('groups.edit');
        Route::put('/grupos/{group}', [GroupController::class, 'update'])->name('groups.update');
        Route::delete('/grupos/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    });

    // Rotas comuns (acessíveis a todos os usuários autenticados)
    Route::get('/materiais', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materiais/{material}', [MaterialController::class, 'show'])->name('materials.show');
});

require __DIR__.'/auth.php';
