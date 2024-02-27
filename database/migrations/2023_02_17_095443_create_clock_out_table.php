<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClockOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clockOuts', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->unsigned()->nullable();
            $table->integer('attendance_id')->unsigned();
            $table->dateTime('clock_out')->nullable();
            $table->dateTime('clock_in')->nullable();
            $table->tinyInteger('type')->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->foreign('emp_id')->references('id')->on('employees');
            $table->foreign('attendance_id')->references('id')->on('attendances');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clock_out');
    }
}
