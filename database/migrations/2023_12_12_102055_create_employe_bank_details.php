<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employe_bank_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employe_id');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('ifsc_code');
            $table->string('bank_name');
            $table->string('branch_name');
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
        Schema::dropIfExists('employe_bank_details');
    }
}
