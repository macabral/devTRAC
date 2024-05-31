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
        Schema::create('projects', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->enum('status', ['Enabled', 'Disabled']);
            $table->tinyInteger('media_sp')->unsigned()->default(0);
            $table->tinyInteger('media_pf')->unsigned()->default(0);

            $table->timestamps();

            $table->index('status');
            $table->unique(array('title'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
