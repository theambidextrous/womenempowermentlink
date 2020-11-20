<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamprogressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_progress', function (Blueprint $table) {
            //'exam', 'student', 'question_id', 'q_index', 'correct', 'selected', 'maxscore', 'is_locked',
            $table->id();
            $table->string('exam', 20);
            $table->string('student', 20);
            $table->string('question_id', 20);
            $table->string('q_index', 20);
            $table->string('correct', 2);
            $table->string('selected', 20)->nullable();
            $table->string('maxscore', 4)->nullable();
            $table->boolean('is_locked')->default(false);
            $table->unique(['exam', 'student', 'question_id']);
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
        Schema::dropIfExists('exam_progress');
    }
}
