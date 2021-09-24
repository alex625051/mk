<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActressesFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actress_film', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actress_id');
            $table->unsignedBigInteger('film_id');
            $table->foreign('actress_id')
                ->references('id')
            ->on('actresses')->onDelete('cascade');
            $table->foreign('film_id')
                ->references('id')
                ->on('films')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actresses_films');
    }
}
