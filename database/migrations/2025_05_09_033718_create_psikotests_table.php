<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsikotestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psikotests', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id');
            $table->unsignedBigInteger('decision_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->unsignedBigInteger('score');
            $table->string('notes');
            $table->boolean('notification_sent')->default(false);
            $table->boolean('info_sent')->default(false);
            $table->timestamps();

            $table->foreign('applicant_id')
                ->references('id')
                ->on('applicant')
                ->onDelete('cascade');

            $table->foreign('decision_id')
                ->references('id')
                ->on('decisions')
                ->onDelete('cascade');

            $table->foreign('staff_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psikotests');
    }
}
