<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganSystemSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organ_system_symptom', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('organ_system_id')->unsigned();
            $table->integer('symptom_id')->unsigned();
            $table->timestamps();

            $table->foreign('organ_system_id')
                ->references('id')
                ->on('organ_systems')
                ->onDelete('cascade');

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
        Schema::dropIfExists('organ_system_symptoms');
    }
}
