<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(FacilitySeeder::class);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'), // Ensure to hash the password
            'role_id' => 1, // Assign a role ID, assuming 'admin' is the first role
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'student',
            'email' => 'student@gmail.com',
            'password' => bcrypt('student123'), // Ensure to hash the password
            'role_id' => 2, // Assign a role ID, assuming 'student' is the second role
            'status' => 'active',
            'student_id' => '22SBR12345', // Example student ID
        ]);

        User::factory()->create([
            'name' => 'public',
            'email' => 'public@gmail.com',
            'password' => bcrypt('public123'), // Ensure to hash the password
            'role_id' => 3, // Assign a role ID, assuming 'public' is the third role
            'status' => 'active',
        ]);
    }
}
