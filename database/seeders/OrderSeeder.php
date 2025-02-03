<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Material;
use App\Models\Requester;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $statusOptions = ['new', 'in_review', 'changes_requested'];
        $requesters = Requester::all();
        $materials = Material::all();

        foreach ($requesters as $requester) {
            // Criar alguns pedidos para cada solicitante
            for ($i = 0; $i < 3; $i++) {
                $order = Order::create([
                    'requester_id' => $requester->id,
                    'group_id' => $requester->group_id,
                    'total' => 0,
                    'status' => $statusOptions[rand(0, 2)],
                    'created_date' => now(),
                    'updated_date' => now()
                ]);

                // Adicionar materiais aleatórios ao pedido
                $orderTotal = 0;
                $randomMaterials = $materials->random(rand(1, 4));

                foreach ($randomMaterials as $material) {
                    $quantity = rand(1, 5);
                    $subtotal = $material->price * $quantity;
                    $orderTotal += $subtotal;

                    $order->materials()->attach($material->id, [
                        'quantity' => $quantity,
                        'subtotal' => $subtotal
                    ]);
                }

                // Atualizar o total do pedido
                $order->update(['total' => $orderTotal]);
            }
        }
    }
}
