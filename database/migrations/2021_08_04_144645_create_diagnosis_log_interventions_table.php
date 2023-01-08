<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosisLogInterventionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosis_log_interventions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('diagnosis_log_id')->unsigned();
            $table->integer('intervention_id')->unsigned();

            $table->timestamps();

            $table->foreign('diagnosis_log_id')
                    ->references('id')
                    ->on('diagnosis_logs')
                    ->onDelete('cascade');     

            //Need to discuss handling of deletion of intervention (rather than delete in db might be disabled -> and not included in API)
            $table->foreign('intervention_id')
                    ->references('id')
                    ->on('interventions')
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
        Schema::dropIfExists('diagnosis_log_interventions');
    }
}
