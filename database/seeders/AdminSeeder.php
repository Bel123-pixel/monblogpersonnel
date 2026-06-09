<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'bignonhounty@gmail.com'],
            [
                'name'     => 'HOUNTY Bignon',
                'username' => 'bignon_hounty',
                'email'    => 'bignonhounty@gmail.com',
                'password' => Hash::make('bignon1234'),
                'is_admin' => true,
            ]
        );
    }
}
