<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnerExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learner_exams', function (Blueprint $table) {
            $table->id();
            $table->string('learner', 20);
            $table->string('exam', 20);
            $table->text('learner_answer');
            $table->string('markedby', 20)->default(0);
            $table->string('score', 20);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_marked')->default(false);
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
        Schema::dropIfExists('learner_exams');
    }
}
