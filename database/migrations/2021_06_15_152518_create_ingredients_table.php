<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->decimal('dm', 5, 2)->default('0.0');
            $table->decimal('me', 5, 2)->default('0.0');
            $table->decimal('cp', 5, 2)->default('0.0');
            $table->decimal('ndf', 5, 2)->default('0.0');
            $table->decimal('tdn', 5, 2)->default('0.0');
            $table->decimal('ca', 5, 2)->default('0.0');
            $table->decimal('p', 5, 2)->default('0.0');
            $table->decimal('min', 5, 2)->default('0.0');
            $table->decimal('max', 5, 2)->default('0.0');
            $table->boolean('preload')->default(0);
            


            $table->integer('category_id')->unsigned()->nullable();

            $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
}
