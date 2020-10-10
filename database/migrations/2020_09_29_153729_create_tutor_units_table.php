<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutorUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutor_units', function (Blueprint $table) {
            //'tutor', 'unit','is_deleted',
            $table->id();
            $table->string('tutor', 20);
            $table->string('unit', 20);
            $table->boolean('is_deleted')->default(false);
            $table->unique(['tutor', 'unit']);
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
        Schema::dropIfExists('tutor_units');
    }
}
