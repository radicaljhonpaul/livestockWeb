<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('local_term');
            $table->integer('organ_system_id')->unsigned()->nullable();
            $table->string('common_in_region')->nullable();
            $table->longText('description');
            // Keep this one - as this is being used within the app
            $table->longText('how_to_diganose');
            $table->string('treatment', 1000);
            $table->string('advice_to_farmer', 480);
            $table->string('preventive_measure', 1000);
            $table->boolean('quick_action')->default(1);
            $table->boolean('zoonotic')->default(0);

            $table->foreign('organ_system_id')
                 ->references('id')
                 ->on('organ_systems')
                 ->onDelete('set null');           

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
        Schema::dropIfExists('health_conditions');
    }
}
