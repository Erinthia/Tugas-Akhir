<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permissions_id');
            $table->unsignedBigInteger('roles_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('permissions_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('roles_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            // Prevent duplicate entries
            $table->unique(['permissions_id', 'roles_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_role');
    }
};
