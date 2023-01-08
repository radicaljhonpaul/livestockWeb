<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosisLogHealthConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosis_log_health_conditions', function (Blueprint $table) {
           
            $table->bigIncrements('id');
            
            $table->integer('diagnosis_log_id')->unsigned();
            $table->integer('health_condition_id')->unsigned();

            $table->timestamps();

            $table->foreign('diagnosis_log_id')
                  ->references('id')
                  ->on('diagnosis_logs')
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
        Schema::dropIfExists('diagnosis_log_health_conditions');
    }
}
