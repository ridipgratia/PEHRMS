<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangePasswordVerifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_password_verify', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->integer('secure_number');
            $table->text('hash_url')->unique();
            $table->dateTime('expire_time');
            $table->integer('active')->default(1);
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
        Schema::dropIfExists('change_password_verify');
    }
}
