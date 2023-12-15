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
            $table->string('promoted_to_curr_des');
            $table->string('promoted_from_curr_des');

            $table->string('bdo_status'); // Whether In charge BDO/ GP secretary (select in charge BDO status
            $table->integer('transferred_from_district');
            $table->integer('transferred_from_block');
            $table->integer('transferred_from_gp');
            $table->integer('transferred_to_district');
            $table->integer('transferred_to_block');
            $table->integer('transferred_to_gp');
            $table->string('transferred_document');
            $table->date('transferred_date');
            $table->string('previous_joining_document');
            $table->date('previous_joining_date');
            $table->string('service_branch');
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
