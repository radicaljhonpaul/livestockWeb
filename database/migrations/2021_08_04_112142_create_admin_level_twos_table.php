<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLevelTwosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_level_twos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');    
            $table->integer('admin_level_one_id')->unsigned()->nullable();        
            
            $table->foreign('admin_level_one_id')
                  ->references('id')
                  ->on('admin_level_ones')
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
        Schema::dropIfExists('admin_level_twos');
    }
}
