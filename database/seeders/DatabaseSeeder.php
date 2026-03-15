<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use \Illuminate\Database\Console\Seeds\WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            SemesterSeeder::class,
            MajorSeeder::class,
            SubjectSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'forumAdmin@finki.com'],
            ['name' => 'Admin', 'password' => bcrypt('FinkiForum26'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'student@finki.edu.mk'],
            ['name' => 'Test Student', 'password' => bcrypt('password'), 'role' => 'student']
        );
    }
}
