<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarksAndUpdatedByToClockoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clockouts', function (Blueprint $table) {
            $table->text('remarks')->nullable(); 
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clockouts', function (Blueprint $table) {
            $table->dropColumn('remarks'); 
            $table->dropColumn('updated_by'); 
        });
    }
}
