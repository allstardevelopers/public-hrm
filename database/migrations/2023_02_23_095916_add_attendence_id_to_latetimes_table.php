<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendenceIdToLatetimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('latetimes', function (Blueprint $table) {
            $table->integer('attendance_id')->unsigned()->after('id')->nullable();
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('latetimes', function (Blueprint $table) {
            $table->dropForeign(['attendance_id']);
            $table->dropColumn('attendance_id');
        });
    }
}
