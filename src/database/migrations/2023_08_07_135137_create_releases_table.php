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
        Schema::create('releases', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->biginteger('projects_id')->unsigned();
            $table->string('version', 30);
            $table->longText('description')->nullable();
            $table->enum('status', ['Open', 'Closed', 'Waiting']);

            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');

            $table->timestamps();

            $table->index('version');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
