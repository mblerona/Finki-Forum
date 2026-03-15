<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 8) as $number) {
            Semester::firstOrCreate(['name' => "Semester $number"]);
        }
    }
}
