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
        Schema::create('logtickets', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->longText('description');
            $table->biginteger('tickets_id')->unsigned();
            $table->biginteger('users_id')->unsigned();
            $table->foreign('tickets_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('restrict');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_tickets');
    }
};
