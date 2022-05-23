<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddEditedByToPeopleAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people_attendance', function (Blueprint $table) {
            $table->string('edited_by')->nullable();
            $table->timestamp('edited_at')->useCurrent();
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
            $table->dropColumn('edited_by');
            $table->dropColumn('edited_at');
        });
    }
}
