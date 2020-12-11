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
            'name' => 'Admin dos Santos',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'cpf_cnpj' => '22222222222',
            'phone' => '11582245700',
            'type' => 'admin',
            'active' => true,
            'activation_token' => Str::random(60),
        ]);
    }
}
