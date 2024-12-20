<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    use HasFactory;


    protected $fillable = [
        'nom',
        'logo',
        'pays_dorigine',
        'description',
        'status',
    ];

    public function modeles()
    {
        return $this->hasMany(Model::class);
    }

}
