<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivestocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livestocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('carabao_code')->unique();
            $table->char('breed', 2)->nullable();
            $table->char('sex', 2);
            $table->integer('year_of_birth')->nullable();
            $table->date('registration_date')->nullable();
            $table->boolean('is_pregnant');

            $table->integer('farmer_id')->unsigned()->nullable();

            $table->foreign('farmer_id')
                  ->references('id')
                  ->on('farmers')
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
        Schema::dropIfExists('livestocks');
    }
}
