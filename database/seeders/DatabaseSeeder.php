<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'), // Ensure to hash the password
            'role_id' => 1, // Assign a role ID, assuming 'admin' is the first role
        ]);

        User::factory()->create([
            'name' => 'student',
            'email' => 'student@gmail.com',
            'password' => bcrypt('student123'), // Ensure to hash the password
            'role_id' => 2, // Assign a role ID, assuming 'student' is the second role
        ]);

        User::factory()->create([
            'name' => 'public',
            'email' => 'public@gmail.com',
            'password' => bcrypt('public123'), // Ensure to hash the password
            'role_id' => 3, // Assign a role ID, assuming 'public' is the third role
        ]);

        $this->call(RoleSeeder::class);
    }
}
