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
        Schema::create('documents', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->biginteger('projects_id')->unsigned();
            $table->biginteger('tipodocs_id')->unsigned();
            $table->biginteger('users_id')->unsigned();
            $table->string('title', 255);
            $table->string('file', 255)->nullable();
            $table->date('datadoc');

            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('tipodocs_id')->references('id')->on('tipodocs')->onDelete('restrict');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('restrict');

            $table->index('datadoc');

            $table->timestamps();

        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprints');
    }
};
