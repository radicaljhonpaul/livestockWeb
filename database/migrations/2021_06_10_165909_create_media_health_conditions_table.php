<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaHealthConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_health_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('health_condition_id')->unsigned();
            $table->string('path_name');
            $table->string('type', 5);            

            $table->timestamps();

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
        Schema::dropIfExists('media_health_conditions');
    }
}
