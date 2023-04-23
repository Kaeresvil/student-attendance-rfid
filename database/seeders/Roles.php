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
        DB::table('roles')->delete();
        DB::table('roles')->insert([
            'role_name' => 'Admin',
            'code' => 'Administrator',
            'description' => 'Administrator have an access all features.',

        ]);
        DB::table('roles')->insert([
            'role_name' => 'Teacher',
            'code' => 'Teacher',
            'description' => 'Teacher have an access to add events, students and view reports.',

        ]);
        DB::table('roles')->insert([
            'role_name' => 'Head Teacher',
            'code' => 'Head Teacher',
            'description' => 'Head Teacher have an access only to view students attendance reports.',

        ]);
    }
}
