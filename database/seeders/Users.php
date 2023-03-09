<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            'name' => 'Admin',
            'role_type_id' => '1',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('poiuy'),
        ]);
        DB::table('users')->insert([
            'name' => 'Teacher',
            'role_type_id' => '2',
            'email' => 'teacher@gmail.com',
            'password' => bcrypt('poiuy'),
        ]);
        DB::table('users')->insert([
            'name' => 'Head Teacher',
            'role_type_id' => '2',
            'email' => 'hteacher@gmail.com',
            'password' => bcrypt('poiuy'),
        ]);
    }
}
