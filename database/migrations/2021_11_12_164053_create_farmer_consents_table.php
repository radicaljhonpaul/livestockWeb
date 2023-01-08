<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerConsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_consents', function (Blueprint $table) {
            
            $table->bigIncrements('id')->unsigned();
            
            $table->string('type');             // consent for sms or program
            $table->string('method');           // sms / app / manual
            $table->string('details');          // for sms - will contain sender number, for app will contain username, for manual - batch details
            $table->boolean('consent')->default(0);

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
        Schema::dropIfExists('farmer_consents');
    }
}
