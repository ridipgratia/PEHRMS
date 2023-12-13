<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeServiceRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employe_service_record', function (Blueprint $table) {
            $table->id();
            $table->integer('employe_id');
            $table->string('promoted_to_curr_des')->nullable();
            $table->string('promoted_from_curr_des')->nullable();

            $table->string('bdo_status')->nullable(); // Whether In charge BDO/ GP secretary (select in charge BDO status
            $table->integer('transferred_from_district')->nullable();
            $table->integer('transferred_from_block')->nullable();
            $table->integer('transferred_from_gp')->nullable();
            $table->integer('transferred_to_district')->nullable();
            $table->integer('transferred_to_block')->nullable();
            $table->integer('transferred_to_gp')->nullable();
            $table->string('transferred_document')->nullable();
            $table->date('transferred_date')->nullable();
            $table->string('joining_document')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('service_branch')->nullable();
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
        Schema::dropIfExists('employe_service_record');
    }
}
