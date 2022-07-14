<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('table_id');
            $table->integer('table_number');
            $table->string('company_name');
            $table->string('company_email');
            $table->text('phone_number');
            $table->text('commercial_record');
            $table->text('photo');
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
        Schema::dropIfExists('register_requests');
    }
};
