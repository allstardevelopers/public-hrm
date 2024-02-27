<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFridayAndOtherdayToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('friday_break')->nullable()->after('time_out');
            $table->string('otherday_break')->nullable()->after('friday_break');
            $table->string('halfday_break')->nullable()->after('otherday_break');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('friday_break');
            $table->dropColumn('otherday_break');
            // $table->dropColumn('halfday_break');
        });
    }
}
