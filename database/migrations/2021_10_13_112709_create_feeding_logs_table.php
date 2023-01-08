<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeding_logs', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('external_id', 255);
            $table->dateTime('visit_date');
            $table->decimal('body_weight', 5, 2)->default('0.0');
            $table->string('category',100);
            $table->boolean('is_lactating')->nullable();
            $table->string('lactation_stage', 100)->nullable();
            $table->boolean('is_pregnant')->nullable();
            $table->integer('pregnancy_month')->nullable();
            $table->decimal('ave_daily_gain', 3, 2)->default('0.0')->nullable();
            $table->decimal('milk_yield_per_day', 5, 2)->nullable();
            $table->decimal('fat_protein', 4, 2)->nullable();
            $table->decimal('milk_price', 6, 2)->nullable();
            $table->decimal('total_cost_per_day', 6, 2)->default('0.0')->nullable();
            $table->decimal('feed_cost_per_kg', 6, 2)->default('0.0')->nullable();
            $table->decimal('total_as_fed_kg', 6, 2)->default('0.0')->nullable();
            $table->decimal('income', 6, 2)->default('0.0');

            $table->string('ration_name', 255)->nullable();

            $table->integer('livestock_id')->unsigned();
            $table->bigInteger('created_by')->unsigned()->nullable();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');    

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->integer('srp_year_id')->unsigned()->nullable();
            $table->foreign('srp_year_id')
                ->references('id')
                ->on('srp_years')
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
        Schema::dropIfExists('feeding_logs');
    }
}
