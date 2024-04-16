<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \App\Models\User::factory()->create([
        'name' => 'Admin',
        'email' => 'test@admin.com',
        'phone' => '9876543210',
        'password' => bcrypt('password'),
        'is_admin' => 'true',
         ]);
        }
    
}
