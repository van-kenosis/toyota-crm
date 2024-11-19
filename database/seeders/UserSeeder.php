<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use App\Models\Usertype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminType = Usertype::where('name', 'SuperAdmin')->first();
        $team = Team::where('name', 'Team 1')->first();
        if (!$superAdminType) {
            return;
        }

        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@gmail.com',
            'usertype_id' => $superAdminType->id,
            'team_id' => $team->id,
            'password' => Hash::make('123456'),
        ]);

    }
}
