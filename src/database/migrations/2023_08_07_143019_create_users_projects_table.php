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
        Schema::create('users_projects', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->biginteger('users_id')->unsigned();
            $table->biginteger('projects_id')->unsigned();
            $table->enum('gp', [0, 1]);
            $table->enum('relator', [0, 1]);
            $table->enum('dev', [0, 1]);
            $table->enum('tester', [0, 1]);

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_projects');
    }
};
