<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Services\MaterialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class MaterialController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
        $this->middleware('auth');
    }

    /**
     * Listar materiais
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $materials = $this->materialService->search($request->search);
        } else {
            $materials = $this->materialService->list();
        }

        return view('materials.index', compact('materials'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        if (Auth::user()->profile !== 'admin') {
            return redirect()->route('materials.index')
                ->with('error', 'Apenas administradores podem criar materiais.');
        }

        return view('materials.create');
    }

    /**
     * Criar novo material
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:45|unique:materials',
            'price' => 'required|numeric|min:0.01'
        ]);

        try {
            $material = $this->materialService->create($request->all(), Auth::user());
            return redirect()->route('materials.show', $material)
                ->with('success', 'Material criado com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar detalhes do material
     */
    public function show(Material $material)
    {
        return view('materials.show', compact('material'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Material $material)
    {
        if (Auth::user()->profile !== 'admin') {
            return redirect()->route('materials.index')
                ->with('error', 'Apenas administradores podem editar materiais.');
        }

        return view('materials.edit', compact('material'));
    }

    /**
     * Atualizar material
     */
    public function update(Request $request, Material $material)
    {
        $request->validate([
            'name' => 'required|string|max:45|unique:materials,name,' . $material->id,
            'price' => 'required|numeric|min:0.01'
        ]);

        try {
            $material = $this->materialService->update($material, $request->all(), Auth::user());
            return redirect()->route('materials.show', $material)
                ->with('success', 'Material atualizado com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Excluir material
     */
    public function destroy(Material $material)
    {
        try {
            $this->materialService->delete($material, Auth::user());
            return redirect()->route('materials.index')
                ->with('success', 'Material excluído com sucesso.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Relatório de uso dos materiais
     */
    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        try {
            $report = $this->materialService->getUsageReport(
                $request->start_date,
                $request->end_date
            );

            return view('materials.report', compact('report'));
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
