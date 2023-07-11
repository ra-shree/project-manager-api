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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->boolean('completed')->default(false);
            $table->unsignedBigInteger('assigned_to_id')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('creator_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('assigned_to_id')->references('id')->on('users');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
