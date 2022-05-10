<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalCalculatedHoursToPeopleAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people_attendance', function (Blueprint $table) {
            $table->float('h_125')->default(0);
            $table->float('h_150')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people_attendance', function (Blueprint $table) {
            //
        });
    }
}
