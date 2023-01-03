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
            'name' => 'John John',
            'email' => 'john@john.com',
            'password' => Hash::make('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Filbert Filbert',
            'email' => 'filbert@filbert.com',
            'password' => Hash::make('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Andreas Andreas',
            'email' => 'andreas@andreas.com',
            'password' => Hash::make('password')
        ]);
    }
}
