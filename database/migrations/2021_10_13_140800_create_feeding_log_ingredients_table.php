<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedingLogIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeding_log_ingredients', function (Blueprint $table) {
          
            $table->increments('id');

            $table->integer('feeding_log_id')->unsigned();
            $table->integer('ingredient_id')->unsigned();

            $table->decimal('quantity', 7, 2)->default('0.0');
            $table->decimal('price_at_date', 7, 2)->default('0.0');
            

            $table->foreign('feeding_log_id')
                ->references('id')
                ->on('feeding_logs')
                ->onDelete('cascade');  

            $table->foreign('ingredient_id')
                ->references('id')
                ->on('ingredients')
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
        Schema::dropIfExists('feeding_log_ingredients');
    }
}
