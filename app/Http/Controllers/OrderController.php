<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Listar pedidos
     */
    public function index()
    {
        return view('orders.index');
    }

    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        if (Auth::user()->profile !== 'requester') {
            return redirect()->route('orders.index')
                ->with('error', 'Apenas solicitantes podem criar pedidos.');
        }

        return view('orders.create');
    }

    /**
     * Criar novo pedido
     */
    public function store(Request $request)
    {
        $request->validate([
            'materials' => 'required|array|min:1',
            'materials.*.id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1'
        ]);

        try {
            $order = $this->orderService->create($request->all(), Auth::user());
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido criado com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar detalhes do pedido
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Aprovar pedido
     */
    public function approve(Order $order)
    {
        try {
            $this->orderService->approve($order, Auth::user());
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido aprovado com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Rejeitar pedido
     */
    public function reject(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        try {
            $this->orderService->reject($order, Auth::user(), $request->reason);
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido rejeitado com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Solicitar alterações no pedido
     */
    public function requestChanges(Request $request, Order $order)
    {
        $request->validate([
            'changes' => 'required|string|min:10'
        ]);

        try {
            $this->orderService->requestChanges($order, Auth::user(), $request->changes);
            return redirect()->route('orders.show', $order)
                ->with('success', 'Solicitação de alterações enviada com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Enviar pedido para revisão
     */
    public function sendToReview(Order $order)
    {
        try {
            $this->orderService->sendToReview($order, Auth::user());
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido enviado para revisão com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
