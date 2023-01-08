<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterventionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interventions', function (Blueprint $table) {
            
            $table->increments('id');
            $table->timestamps();
            $table->integer('health_condition_id')->unsigned();
            $table->string('description');
            $table->boolean('need_license')->default(0);    //indicator if this intervention is only possible for licensed vet
            $table->boolean('pregnant_applicable')->default(0);

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
        Schema::dropIfExists('interventions');
    }
}
