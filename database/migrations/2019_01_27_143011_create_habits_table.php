<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHabitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habits', function (Blueprint $habits) {
            $habits->increments('id');
            $habits->boolean('archived');
            $habits->text('name');
            $habits->text('description');
            // LoopHabits defines frequency in terms of (numerator/denominator)
            $habits->integer('freq_num');
            $habits->integer('freq_den');
            $habits->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('habits');
    }
}
