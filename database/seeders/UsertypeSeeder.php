<?php

namespace Database\Seeders;

use App\Models\Usertype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsertypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Usertype::create([
            'name' => 'SuperAdmin',
        ]);
        Usertype::create([
            'name' => 'Branch Manager',
        ]);
        Usertype::create([
            'name' => 'Team Manager',
        ]);
        Usertype::create([
            'name' => 'Financing Agent',
        ]);
        Usertype::create([
            'name' => 'Agent',
        ]);

    }
}
