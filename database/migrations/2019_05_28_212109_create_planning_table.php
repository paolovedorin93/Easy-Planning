;<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('operator');
            $table->date('date')->nullable();
            $table->date('startDate')->nullable();
            $table->string('activity');
            $table->string('type', 50);
            $table->tinyInteger('hour');
            $table->string('edit');
            $table->tinyInteger('particular');
            $table->tinyInteger('repetition');
            $table->tinyInteger('notification');
            $table->
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
        Schema::dropIfExists('plannings');
    }
}
