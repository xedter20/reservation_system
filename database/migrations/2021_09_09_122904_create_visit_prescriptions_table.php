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
        Schema::create('visit_prescriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('visit_id');
            $table->string('prescription_name');
            $table->string('frequency');
            $table->string('duration');
            $table->text('description');
            $table->timestamps();

            $table->foreign('visit_id')->references('id')->on('visits')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_prescriptions');
    }
};
