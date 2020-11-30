<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Franklin',
            'email' => 'franklin@gmail.com',
            'password' => '123456',
            'cpf_cnpj' => '11111111111',
            'phone' => '11982245700',
            'type' => 'admin',
            'active' => true,
            'activation_token' => Str::random(60),
        ]);
    }
}
