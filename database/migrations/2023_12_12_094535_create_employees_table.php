<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employe_code')->unique();
            $table->string('employe_name');
            $table->string('employe_designation');
            $table->integer('service_status');
            $table->string('employe_phone')->unique();
            $table->string('employe_category');
            $table->string('employe_alt_number');
            $table->string('employe_email')->unique();
            $table->string('employe_profile')->nullable();
            $table->string('employe_father_name');
            $table->string('employe_mother_name');
            $table->date('employe_dob');
            $table->string('employe_birth_certificate');
            $table->string('pan_number');
            $table->string('aadhar_number');
            $table->string('gender');
            $table->string('nationality');
            $table->string('personal_marks_of_identification')->nullable();
            $table->string('caste');
            $table->string('race');
            $table->string('pwd_document')->nullable();
            $table->integer('posted_district');
            $table->integer('posted_block');
            $table->integer('posted_gp');
            $table->date('date_of_order');
            $table->string('order_document');
            $table->date('date_of_joining');
            $table->string('joining_document');
            $table->string('branch');
            $table->date('initial_date_of_joining');
            $table->string('initial_appointment_letter');
            $table->string('initial_joining_letter');
            $table->integer('district');
            $table->integer('block');
            $table->integer('gp');
            $table->string('address');
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
        Schema::dropIfExists('employees');
    }
}
