<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class GroupController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
        $this->middleware('auth');
    }

    /**
     * Listar grupos
     */
    public function index()
    {
        try {
            $groups = $this->groupService->listAccessibleGroups(Auth::user());
            return view('groups.index', compact('groups'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        if (Auth::user()->profile !== 'admin') {
            return redirect()->route('groups.index')
                ->with('error', 'Apenas administradores podem criar grupos.');
        }

        return view('groups.create');
    }

    /**
     * Criar novo grupo
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'allowed_balance' => 'required|numeric|min:0',
            'approver_id' => 'required|exists:users,id'
        ]);

        try {
            $group = $this->groupService->create($request->all(), Auth::user());
            return redirect()->route('groups.show', $group)
                ->with('success', 'Grupo criado com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar detalhes do grupo
     */
    public function show(Group $group)
    {
        return view('groups.show', compact('group'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Group $group)
    {
        if (Auth::user()->profile !== 'admin') {
            return redirect()->route('groups.index')
                ->with('error', 'Apenas administradores podem editar grupos.');
        }

        return view('groups.edit', compact('group'));
    }

    /**
     * Atualizar grupo
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'allowed_balance' => 'required|numeric|min:0',
            'approver_id' => 'required|exists:users,id'
        ]);

        try {
            $group = $this->groupService->update($group, $request->all(), Auth::user());
            return redirect()->route('groups.show', $group)
                ->with('success', 'Grupo atualizado com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Excluir grupo
     */
    public function destroy(Group $group)
    {
        try {
            $this->groupService->delete($group, Auth::user());
            return redirect()->route('groups.index')
                ->with('success', 'Grupo excluído com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
