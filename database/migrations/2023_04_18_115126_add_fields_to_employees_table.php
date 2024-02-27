<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            //
            $table->date('joining_date')->nullable()->after('position');
            $table->string('probation')->nullable()->after('joining_date');;
            $table->string('contact_no')->nullable()->after('email');
            $table->string('emergency_no')->nullable()->after('contact_no');
            $table->date('resign_date')->nullable()->after('tracking');
            $table->tinyInteger('status')->default(1)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            //
            $table->dropColumn('joining_date');
            $table->dropColumn('probation');
            $table->dropColumn('contact_no');
            $table->dropColumn('emergency_no');
            $table->dropColumn('resign_date');
            $table->dropColumn('status');
        });
    }
}
