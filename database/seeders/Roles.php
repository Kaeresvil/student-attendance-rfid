<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role_name' => 'Admin',
            'description' => 'Sample Admin',

        ]);
        DB::table('roles')->insert([
            'role_name' => 'User',
            'description' => 'Sample Users',

        ]);
    }
}
