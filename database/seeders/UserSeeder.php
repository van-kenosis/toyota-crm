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
        // $usertypes = Usertype::all();
        // $team = Team::where('name', 'Team 1')->first();

        // for ($i = 1; $i <= 10; $i++) {
        //     User::create([
        //         'first_name' => 'User' . $i,
        //         'last_name' => 'Doe',
        //         'email' => 'user' . $i . '@gmail.com',
        //         'usertype_id' => $usertypes->random()->id,
        //         'team_id' => rand(1, 5),
        //         'password' => Hash::make('123456'),
        //     ]);
        // }

        User::create([
            'first_name' => 'SuperAdmin',
            'last_name' => 'SuperAdmin',
            'email' => 'crudph.dev@gmail.com',
            'usertype_id' => 1,
            'team_id' => null,
            'password' => Hash::make('qwerty...'),
        ]);

    }
}
