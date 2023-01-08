<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_settings', function (Blueprint $table) {
             
            $table->bigIncrements('id')->unsigned();

            $table->string('key')->unique();
            $table->string('value', 500);
            $table->string('description', 500);

            $table->bigInteger('last_update_by')->unsigned()->nullable();
            $table->foreign('last_update_by')
            ->references('id')
            ->on('users')
            ->onDelete('set null');   

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
        Schema::dropIfExists('config_settings');
    }
}
