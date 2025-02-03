<?php

namespace App\Services;

use App\Models\Material;
use App\Models\User;
use Illuminate\Support\Collection;
use Exception;

class MaterialService
{
    /**
     * Listar todos os materiais disponíveis
     */
    public function list(): Collection
    {
        return Material::orderBy('name')->get();
    }

    /**
     * Criar novo material
     * RNF1: Segurança - Apenas admin pode criar materiais
     */
    public function create(array $data, User $user): Material
    {
        if (!$user->isAdmin()) {
            throw new Exception('Apenas administradores podem criar materiais.');
        }

        if ($data['price'] <= 0) {
            throw new Exception('O preço do material deve ser maior que zero.');
        }

        return Material::create([
            'name' => $data['name'],
            'price' => $data['price']
        ]);
    }

    /**
     * Atualizar material existente
     * RNF1: Segurança - Apenas admin pode atualizar materiais
     */
    public function update(Material $material, array $data, User $user): Material
    {
        if (!$user->isAdmin()) {
            throw new Exception('Apenas administradores podem atualizar materiais.');
        }

        if (isset($data['price']) && $data['price'] <= 0) {
            throw new Exception('O preço do material deve ser maior que zero.');
        }

        $material->update($data);
        return $material->fresh();
    }

    /**
     * Excluir material
     * RNF1: Segurança - Apenas admin pode excluir materiais
     */
    public function delete(Material $material, User $user): bool
    {
        if (!$user->isAdmin()) {
            throw new Exception('Apenas administradores podem excluir materiais.');
        }

        // Verificar se o material está em uso em algum pedido
        if ($material->orders()->exists()) {
            throw new Exception('Não é possível excluir um material que já foi usado em pedidos.');
        }

        return $material->delete();
    }

    /**
     * Buscar materiais por nome
     */
    public function search(string $term): Collection
    {
        return Material::where('name', 'like', "%{$term}%")
            ->orderBy('name')
            ->get();
    }

    /**
     * Obter relatório de uso dos materiais
     */
    public function getUsageReport(string $startDate, string $endDate): array
    {
        $materials = Material::with(['orders' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_date', [$startDate, $endDate])
                ->where('status', 'approved');
        }])->get();

        $report = [];
        foreach ($materials as $material) {
            $totalQuantity = $material->orders->sum('pivot.quantity');
            $totalValue = $material->orders->sum('pivot.subtotal');

            if ($totalQuantity > 0) {
                $report[] = [
                    'material_id' => $material->id,
                    'material_name' => $material->name,
                    'current_price' => $material->price,
                    'total_quantity' => $totalQuantity,
                    'total_value' => $totalValue,
                    'average_price' => $totalValue / $totalQuantity
                ];
            }
        }

        return $report;
    }
}
