<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_symptoms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('symptom_id')->unsigned();
            $table->string('path_name');
            $table->string('type', 5);

            $table->timestamps();

            $table->foreign('symptom_id')
                    ->references('id')
                    ->on('symptoms')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_symptoms');
    }
}
