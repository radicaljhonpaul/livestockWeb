<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('visit_date');

            $table->integer('farmer_id')->unsigned();
            $table->integer('livestock_id')->unsigned();
            $table->integer('diagnosis_log_id')->unsigned()->nullable();
            $table->integer('feeding_log_id')->unsigned()->nullable();
            $table->bigInteger('assigned_to')->unsigned()->nullable();

            $table->foreign('farmer_id')
                  ->references('id')
                  ->on('farmers')
                  ->onDelete('cascade');  

            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');                   

            $table->foreign('livestock_id')
                  ->references('id')
                  ->on('livestocks')
                  ->onDelete('cascade');

            $table->foreign('diagnosis_log_id')
                  ->references('id')
                  ->on('diagnosis_logs')
                  ->onDelete('cascade');
                  
            $table->foreign('feeding_log_id')
                  ->references('id')
                  ->on('feeding_logs')
                  ->onDelete('cascade');   
                  
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_logs');
    }
}
