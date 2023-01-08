<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaDiagnosisLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_diagnosis_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('diagnosis_log_id')->unsigned();
                $table->string('path_name');
                $table->string('type', 4);     
                $table->integer('filesize')->unsigned(); 

                $table->timestamps();

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
        Schema::dropIfExists('media_diagnosis_logs');
    }
}
