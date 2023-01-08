<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pcc_system_id')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->char('gender', '1');
            $table->date('birthdate');
            $table->string('mobile_number')->default('');
            $table->string('phone_number')->default('');
            $table->string('fb_profile')->default('');

            $table->double('lat')->default(0);
            // $table->double('long')->default(0);
            $table->double('longitude')->default(0);
            
            $table->integer('admin_level_three_id')->unsigned()->nullable();

            $table->foreign('admin_level_three_id')
                  ->references('id')
                  ->on('admin_level_threes')
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
        Schema::dropIfExists('farmers');
    }
}
