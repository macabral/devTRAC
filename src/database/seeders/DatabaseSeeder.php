<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'admin' => 1
        ]);

        DB::table('types')->insert([
            'title' => 'Melhoria',
            'status' => 'Enabled',
        ]);

        DB::table('types')->insert([
            'title' => 'Defeito',
            'status' => 'Enabled',
        ]);

        DB::table('projects')->insert([
            'title' => 'Project1',
            'description' => 'Projeto Exemplo',
            'status' => 'Enabled'
        ]);

        DB::table('users_projects')->insert([
            'users_id' => 1,
            'projects_id' => '1',
            'gp' => '1',
            'relator' => '1',
            'dev' => '1',
            'tester' => '1'
        ]);



    }
}
