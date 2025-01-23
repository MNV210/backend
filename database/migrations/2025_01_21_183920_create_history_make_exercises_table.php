<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryMakeExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_make_exercises', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('exercise_id');
            $table->integer('score')->nullable();
            $table->integer('total_question')->nullable();
            $table->integer('correct_answer');
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
        Schema::dropIfExists('history_make_exercises');
    }
}
