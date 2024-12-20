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
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voiture_id');
            $table->unsignedBigInteger('locataire_id');
            $table->text('commentaire');
            $table->enum('statut', ['en attente', 'accepté', 'rejeté'])->default('en attente');
            $table->text('reponse')->nullable();
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
        Schema::dropIfExists('avis');
    }
};
