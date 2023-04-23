<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->delete();
        DB::table('events')->insert([
            'event_name' => 'Class Attendance',
            'event_description' => 'This event is a default for the system.',
            'isSet' => 1

        ]);
    }
}
