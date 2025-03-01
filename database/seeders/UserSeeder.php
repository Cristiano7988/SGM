<?php

namespace Database\Seeders;

use App\Models\User;
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
        $devUser = User::where('email', env('DEV_EMAIL'))->first();
        $dev = [
            'nome' => env('DEV_NAME'),
            'email' => env('DEV_EMAIL'),
            'is_admin' => true,
            'password' => Hash::make(env('DEV_PASSWORD'))
        ];

        $clientUser = User::where('email', env('CLIENT_EMAIL'))->first();
        $client = [
            'nome' => env('CLIENT_NAME'),
            'email' => env('CLIENT_EMAIL'),
            'is_admin' => true,
            'password' => Hash::make(env('CLIENT_PASSWORD'))
        ];

        $accountantUser = User::where('email', env('ACCOUNTANT_EMAIL'))->first();
        $accountant = [
            'nome' => env('ACCOUNTANT_NAME'),
            'email' => env('ACCOUNTANT_EMAIL'),
            'is_admin' => true,
            'password' => Hash::make(env('ACCOUNTANT_PASSWORD'))
        ];

        $dados = [];
        if (env('DEV_NAME') && !$devUser) array_push($dados, $dev);
        if (env('CLIENT_NAME') && !$clientUser) array_push($dados, $client);
        if (env('ACCOUNTANT_NAME') && !$accountantUser) array_push($dados, $accountant);

        DB::table('users')->insert($dados);
    }
}
