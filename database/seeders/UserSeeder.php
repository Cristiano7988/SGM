<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'nome' => env('DEV_NAME'),
            'email' => env('DEV_EMAIL'),
            'is_admin' => true,
            'password' => Hash::make(env('DEV_PASSWORD'))
        ]);
    }
}
