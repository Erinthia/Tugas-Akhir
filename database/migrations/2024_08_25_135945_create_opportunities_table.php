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
        Schema::create('opportunities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->longText('description');
            $table->longText('job_description');
            $table->longText('job_requirements');
            $table->bigInteger('division_id')->unsigned();
            $table->foreign('division_id')->references('id')->on('divisions');
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('clicked')->default(0);
            $table->integer('quota');
            $table->string('location');
            $table->bigInteger('schema_id')->unsigned();
            $table->foreign('schema_id')->references('id')->on('schemas');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
