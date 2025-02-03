<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'profile' => 'admin'
        ]);

        // Aprovadores
        $approvers = [
            [
                'name' => 'João Aprovador',
                'email' => 'joao.aprovador@example.com',
                'password' => Hash::make('password'),
                'profile' => 'approver'
            ],
            [
                'name' => 'Maria Aprovadora',
                'email' => 'maria.aprovadora@example.com',
                'password' => Hash::make('password'),
                'profile' => 'approver'
            ]
        ];

        foreach ($approvers as $approver) {
            User::create($approver);
        }

        // Solicitantes
        $requesters = [
            [
                'name' => 'Pedro Solicitante',
                'email' => 'pedro.solicitante@example.com',
                'password' => Hash::make('password'),
                'profile' => 'requester'
            ],
            [
                'name' => 'Ana Solicitante',
                'email' => 'ana.solicitante@example.com',
                'password' => Hash::make('password'),
                'profile' => 'requester'
            ],
            [
                'name' => 'Carlos Solicitante',
                'email' => 'carlos.solicitante@example.com',
                'password' => Hash::make('password'),
                'profile' => 'requester'
            ]
        ];

        foreach ($requesters as $requester) {
            User::create($requester);
        }
    }
}
