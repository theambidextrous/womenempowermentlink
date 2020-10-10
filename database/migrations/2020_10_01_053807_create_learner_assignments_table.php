<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnerAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learner_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('learner', 20);
            $table->string('assignment', 20);
            $table->string('submission_file');
            $table->string('markedby', 20);
            $table->string('score', 20);
            $table->boolean('is_marked')->default(false);
            $table->boolean('is_deleted')->default(false);
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
        Schema::dropIfExists('learner_assignments');
    }
}
