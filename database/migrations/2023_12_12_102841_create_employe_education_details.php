<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeEducationDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employe_education_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employe_id');
            $table->string('employe_degree');
            $table->string('board_name');
            $table->integer('marks');
            $table->double('percentage');
            $table->string('passing_year');

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
        Schema::dropIfExists('employe_education_details');
    }
}
