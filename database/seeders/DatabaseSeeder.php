<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'institution_id' => 1,
            'is_admin' => true,
        ]);

        $this->call([
            QuestionSeeder::class,
        ]);
    }
}