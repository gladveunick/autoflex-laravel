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
        Schema::create('voitures', function (Blueprint $table) {
            $table->id();
            $table->string('immatriculation')->unique();
            $table->string('image');
            $table->year('annee');
            $table->integer('nbre_siege');
            $table->string('type_carburant');
            $table->string('couleur');
            $table->unsignedBigInteger('categorie_id');
            $table->unsignedBigInteger('marque_id');
            $table->unsignedBigInteger('proprietaire_id');
            $table->boolean('active')->default(false);
            $table->foreign('categorie_id')->references('id')->on('categories');
            $table->foreign('marque_id')->references('id')->on('marques');
            $table->foreign('proprietaire_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voitures');
    }
};
