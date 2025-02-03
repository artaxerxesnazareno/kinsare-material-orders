<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $approvers = User::where('profile', 'approver')->get();

        $groups = [
            [
                'name' => 'Departamento de TI',
                'allowed_balance' => 5000.00,
                'approver_id' => $approvers[0]->id
            ],
            [
                'name' => 'Departamento Financeiro',
                'allowed_balance' => 3000.00,
                'approver_id' => $approvers[0]->id
            ],
            [
                'name' => 'Departamento de RH',
                'allowed_balance' => 2000.00,
                'approver_id' => $approvers[1]->id
            ],
            [
                'name' => 'Departamento Comercial',
                'allowed_balance' => 4000.00,
                'approver_id' => $approvers[1]->id
            ]
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}
