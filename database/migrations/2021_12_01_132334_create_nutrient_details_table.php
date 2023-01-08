<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNutrientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nutrient_details', function (Blueprint $table) {
            
            $table->increments('id');
            $table->char('type', 2);
            $table->decimal('dm', 7, 2)->default('0.0');
            $table->decimal('me', 7, 2)->default('0.0');
            $table->decimal('cp', 7, 2)->default('0.0');
            $table->decimal('ndf', 7, 2)->default('0.0');
            $table->decimal('tdn', 7, 2)->default('0.0');
            $table->decimal('ca', 7, 2)->default('0.0');
            $table->decimal('p', 7, 2)->default('0.0');

            $table->integer('feeding_log_id')->unsigned();
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
        Schema::dropIfExists('nutrient_details');
    }
}
