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
            $table->longText('description')->nullable();
            $table->enum('status', ['Open', 'Closed','Testing']);
            $table->biginteger('sprints_id')->unsigned();
            $table->biginteger('relator_id')->unsigned();
            $table->biginteger('resp_id')->unsigned()->nullable();
            $table->biginteger('projects_id')->unsigned();
            $table->biginteger('types_id')->unsigned();
            $table->tinyInteger('docs')->unsigned()->default(0);
            $table->string('file')->nullable();
            $table->tinyInteger('pf')->unsigned()->default(0);
            $table->longText('testcondition')->nullable();
            $table->tinyInteger('start')->unsigned()->default(0);
            $table->tinyInteger('storypoint')->unsigned()->default(0);
            $table->tinyInteger('valorsp')->unsigned()->default(0);
            $table->enum('prioridade', ['Crítica', 'Importante','Desejada','Pode Esperar']);

            $table->foreign('sprints_id')->references('id')->on('sprints')->onDelete('cascade');
            $table->foreign('resp_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('relator_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('types_id')->references('id')->on('types')->onDelete('restrict');

            $table->timestamps();

            $table->index('projects_id','resp_id');
            $table->index('title');

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
