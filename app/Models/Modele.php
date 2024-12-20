<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque_id',
        'nom',
        'description',
        'status',
    ];

    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }
}
