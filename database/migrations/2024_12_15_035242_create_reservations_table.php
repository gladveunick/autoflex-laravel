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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voiture_id');
            $table->unsignedBigInteger('locataire_id');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nombre_jour');
            $table->decimal('montant_unitaire', 10, 2);
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut', ['en attente', 'validée', 'rejetée', 'annulée'])->default('en attente');
            $table->foreign('voiture_id')->references('id')->on('voitures');
            $table->foreign('locataire_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
