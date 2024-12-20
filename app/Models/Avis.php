<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
        'voiture_id',
        'locataire_id',
        'commentaire',
        'statut',
        'reponse',
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
