<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create(['status' => 'Pending']);
        Status::create(['status' => 'Approved']);
        Status::create(['status' => 'Processed']);
        Status::create(['status' => 'Cancel']);
        Status::create(['status' => 'Denied']);
        Status::create(['status' => 'Cash']);
        Status::create(['status' => 'Released']);
        Status::create(['status' => 'Reserved']);
        Status::create(['status' => 'Pending For Release']);
    }
}
