<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosisLogSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosis_log_symptoms', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('symptom_id')->unsigned();
            $table->integer('diagnosis_log_id')->unsigned();

            $table->timestamps();

            $table->foreign('symptom_id')
                    ->references('id')
                    ->on('symptoms')
                    ->onDelete('cascade');
        
            $table->foreign('diagnosis_log_id')
                    ->references('id')
                    ->on('diagnosis_logs')
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
        Schema::dropIfExists('diagnosis_log_symptoms');
    }
}
