<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@eos.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Engineers
        $engineers = [
            ['name' => 'Budi Santoso', 'email' => 'budi@eos.com'],
            ['name' => 'Siti Aminah', 'email' => 'siti@eos.com'],
            ['name' => 'Made Suardana', 'email' => 'made@eos.com'],
            ['name' => 'Engineer User', 'email' => 'engineer@eos.com'],
        ];

        foreach ($engineers as $engineerData) {
            $engineer = User::create([
                'name' => $engineerData['name'],
                'email' => $engineerData['email'],
                'password' => bcrypt('password'),
                'role' => 'engineer',
            ]);

            // Create 3-5 tasks for each engineer
            \App\Models\Task::factory(rand(3, 5))->create([
                'assigned_to' => $engineer->id,
            ]);
        }

        // Create some unassigned tasks
        \App\Models\Task::factory(5)->create([
            'assigned_to' => null,
        ]);
    }
}
