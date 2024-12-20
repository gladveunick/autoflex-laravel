<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    use HasFactory;

    protected $fillable = [
        'immatriculation',
        'image',
        'annee',
        'nombre_sieges',
        'type_carburant',
        'categorie_id',
        'marque_id',
        'proprietaire_id',
        'status',
    ];

    // Relation avec le propriétaire
    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    // Relation avec la catégorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    // Relation avec la marque
    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }
}
