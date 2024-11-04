<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name' => 'pcmaker',
            'second_name' => 'pcmaker',
            'login' => 'pcmaker',
            'password' => Hash::make('pcmaker')
        ]);
    }
}
