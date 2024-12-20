<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'voiture_id',
        'locataire_id',
        'date_debut',
        'date_fin',
        'nombre_jours',
        'statut',
        'montant_unitaire',
        'montant_total',
    ];

    // Relation avec la voiture
    public function voiture()
    {
        return $this->belongsTo(Voiture::class);
    }

    // Relation avec le locataire
    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

}
