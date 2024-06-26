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
        Schema::create('planningpokers', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->biginteger('tickets_id')->unsigned();
            $table->biginteger('users_id')->unsigned();
            $table->tinyInteger('storypoint')->unsigned()->default(0);
            $table->tinyInteger('valorsp')->unsigned()->default(0);

            $table->timestamps();

            $table->foreign('tickets_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planningpokers');
    }
};
