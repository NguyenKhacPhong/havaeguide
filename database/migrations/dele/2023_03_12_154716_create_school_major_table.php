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
        Schema::create('school_major', function (Blueprint $table) {
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('major_id');
            $table->primary('school_id', 'major_id');
            $table->foreign('school_id')->references('id')->on('schools');
            //$table->foreign('major_id')->references('id')->on('majors');
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
        Schema::dropIfExists('school_major');
    }
};
