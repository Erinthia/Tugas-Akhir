<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportingsTable extends Migration
{
    public function up()
    {
        Schema::create('reportings', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id');
            $table->unsignedBigInteger('decision_id');
            $table->timestamps();

            $table->foreign('applicant_id')
                ->references('id')
                ->on('applicant')
                ->onDelete('cascade');

            $table->foreign('decision_id')
                ->references('id')
                ->on('decisions')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportings');
    }
}
