<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHcSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hc_symptoms', function (Blueprint $table) {
            
            $table->increments('id');
            $table->timestamps();
            $table->integer('symptom_id')->unsigned();
            $table->integer('health_condition_id')->unsigned();
            $table->boolean('differential')->default(0);

            $table->foreign('symptom_id')
                    ->references('id')
                    ->on('symptoms')
                    ->onDelete('cascade');

            $table->foreign('health_condition_id')
                    ->references('id')
                    ->on('health_conditions')
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
        Schema::dropIfExists('hc_symptoms');
    }
}
