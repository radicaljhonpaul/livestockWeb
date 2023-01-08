<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientSrpYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_srp_year', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('ingredient_id')->unsigned();
            $table->integer('srp_year_id')->unsigned();
            $table->decimal('price', 6, 2)->default('0.0');

            $table->foreign('ingredient_id')
                ->references('id')
                ->on('ingredients')
                ->onDelete('cascade');

            $table->foreign('srp_year_id')
                ->references('id')
                ->on('srp_years')
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
        Schema::dropIfExists('ingredient_srp_year');
    }
}
