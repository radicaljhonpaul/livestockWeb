<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosisLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnosis_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_id', 255);
            $table->dateTime('visit_date');
            $table->char('activity_type', 2);
            $table->char('status', 2)->nullable()->default('OP');
            $table->char('assessment', 2);
            $table->string('notes', 1000)->nullable();
            $table->boolean('is_pregnant')->nullable();

            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('authorized_by')->unsigned()->nullable();
            $table->integer('authorization_via')->unsigned()->nullable();
            $table->bigInteger('assigned_to')->unsigned()->nullable();
            $table->dateTime('date_closed')->nullable();
            $table->integer('livestock_id')->unsigned();

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');    

            $table->foreign('authorized_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); 

            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');                   

            $table->foreign('livestock_id')
                  ->references('id')
                  ->on('livestocks')
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
        Schema::dropIfExists('diagnosis_logs');
    }
}
