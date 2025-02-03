<?php
namespace Database\Seeders;
use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Requester;

class RequesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requesters = User::where('profile', 'requester')->get();
        $groups = Group::all();

        // Distribuir os solicitantes entre os grupos
        foreach ($requesters as $index => $requester) {
            Requester::create([
                'user_id' => $requester->id,
                'group_id' => $groups[$index % count($groups)]->id
            ]);
        }
    }
}
