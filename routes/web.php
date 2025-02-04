<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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
        Route::get('/pedidos/{order}/editar', [OrderController::class, 'edit'])->name('orders.edit');
        Route::post('/pedidos/{order}/enviar-revisao', [OrderController::class, 'sendToReview'])
            ->name('orders.send-to-review');
    });

    // Rotas para Aprovadores
    Route::middleware(['approver'])->group(function () {
        Route::get('/aprovador', function () {
            return view('approver.dashboard');
        })->name('approver.dashboard');

        Route::get('/aprovador/pedidos/{order}', function (App\Models\Order $order) {
            return view('approver.show-order', compact('order'));
        })->name('approver.orders.show');

        Route::get('/pedidos/pendentes', [OrderController::class, 'pending'])->name('orders.pending');
        Route::post('/pedidos/{order}/aprovar', [OrderController::class, 'approve'])->name('orders.approve');
        Route::post('/pedidos/{order}/rejeitar', [OrderController::class, 'reject'])->name('orders.reject');
        Route::post('/pedidos/{order}/solicitar-alteracoes', [OrderController::class, 'requestChanges'])
            ->name('orders.request-changes');
    });

    // Rotas para Administradores
    Route::middleware(['admin'])->group(function () {
        // Materiais
        // Rotas comuns (acessíveis a todos os usuários autenticados)
        Route::get('/materiais', function () {
            return view('materials.index');
        })->name('materials.index');
        Route::get('/materiais/{material}', [MaterialController::class, 'show'])->name('materials.show');
        Route::get('/materiais/criar', function () {
            return view('materials.create');
        })->name('materials.create');
        Route::post('/materia\is', [MaterialController::class, 'store'])->name('materials.store');
        Route::get('/materiais/{material}/editar', [MaterialController::class, 'edit'])->name('admin.materials.edit');
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

        // Usuários
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });


});

require __DIR__.'/auth.php';
