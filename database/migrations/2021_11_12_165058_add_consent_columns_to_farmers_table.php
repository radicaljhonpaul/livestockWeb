<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConsentColumnsToFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {

            $table->boolean('sms_consent')->default(0);
            $table->date('sms_consent_date')->nullable();
            $table->string('sms_consent_method')->nullable();

            $table->boolean('program_consent')->default(0);
            $table->date('program_consent_date')->nullable();
            $table->string('program_consent_method')->nullable();
       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmers', function (Blueprint $table) {
            //
        });
    }
}
