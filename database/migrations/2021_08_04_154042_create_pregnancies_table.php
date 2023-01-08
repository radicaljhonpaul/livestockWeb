<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePregnanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('description')->nullable();

            $table->timestamps();

            $table->integer('livestock_id')->unsigned();
            $table->bigInteger('created_by')->unsigned()->nullable();

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');            

            $table->foreign('livestock_id')
                  ->references('id')
                  ->on('livestocks')
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
        Schema::dropIfExists('pregnancies');
    }
}
