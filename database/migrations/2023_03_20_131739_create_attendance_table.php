<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->string('stud_lrn');
            $table->integer('section_id')->unsigned()->index();
            $table->integer('event_id')->unsigned()->index();
            $table->datetime('am_time_in');
            $table->datetime('am_time_out');
            $table->datetime('pm_time_in');
            $table->datetime('pm_time_out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance');
    }
}
