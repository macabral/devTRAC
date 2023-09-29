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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('cc')->nullable();
            $table->string('subject');
            $table->string('title')->nullable();
            $table->longText('body');
            $table->string('attachments')->nullable();
            $table->tinyInteger('sent')->default(0);
            $table->tinyInteger('priority')->default(10);
            $table->timestamps();

            $table->index(['priority','sent']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email');
    }
};
