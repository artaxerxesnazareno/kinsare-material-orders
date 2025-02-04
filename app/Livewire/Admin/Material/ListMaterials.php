<?php

namespace App\Livewire\Admin\Material;

use App\Models\Material;
use Livewire\Component;
use Livewire\WithPagination;

class ListMaterials extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $materials = Material::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.admin.material.list-materials', [
            'materials' => $materials
        ]);
    }

    public function delete(Material $material)
    {
        try {
            $material->delete();
            session()->flash('success', 'Material excluído com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao excluir material: ' . $e->getMessage());
        }
    }
}
