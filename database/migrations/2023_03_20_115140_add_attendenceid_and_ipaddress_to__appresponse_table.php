<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendenceidAndIpaddressToAppresponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_response', function (Blueprint $table) {
                
                $table->unsignedBigInteger('attendance_id')->after('emp_id')->nullable();
                $table->string('ip_address')->after('host_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_response', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'attendance_id']);
        });
    }
}
