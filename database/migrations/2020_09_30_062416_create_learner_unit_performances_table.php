<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearnerUnitPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learner_unit_performances', function (Blueprint $table) {
            $table->id();
            $table->string('learner', 20);
            $table->string('course', 20);
            $table->string('unit', 20);
            $table->string('assessment', 20);
            $table->string('exam', 20);
            $table->string('final', 20);
            $table->string('checkedby', 20);
            $table->boolean('is_passed')->default(false);
            $table->boolean('is_completed')->default(false);
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
        Schema::dropIfExists('learner_unit_performances');
    }
}
