<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;


    protected $fillable = [
        'reservation_id',
        'locataire_id',
        'mode_paiement',
        'montant',
        'statut',
        'reference_paiement',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function locataire()
    {
        return $this->belongsTo(User::class);
    }
}
