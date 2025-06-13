<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offerings', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('benefit');
            $table->string('selection_result');
            $table->date('deadline_offering');
            $table->string('offering_result');
            $table->boolean('notification_sent')->default(false);
            $table->timestamps();

            $table->foreign('applicant_id')
                ->references('id')
                ->on('applicant')
                ->onDelete('cascade');

            $table->foreign('staff_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offerings');
    }
};
