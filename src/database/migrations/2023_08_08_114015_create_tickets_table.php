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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('title');
            $table->longText('description');
            $table->enum('status', ['Open', 'Closed','Testing']);
            $table->biginteger('releases_id')->unsigned();
            $table->biginteger('relator_id')->unsigned();
            $table->biginteger('resp_id')->unsigned()->nullable();
            $table->biginteger('projects_id')->unsigned();
            $table->biginteger('types_id')->unsigned();
            $table->tinyInteger('docs')->unsigned()->default(0);
            $table->string('file')->nullable();

            $table->foreign('releases_id')->references('id')->on('releases')->onDelete('cascade');
            $table->foreign('resp_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('relator_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('types_id')->references('id')->on('types')->onDelete('restrict');

            $table->timestamps();

            $table->index('title');
            $table->index('status');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
