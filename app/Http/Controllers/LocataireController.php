<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voiture;


class LocataireController extends Controller
{
    //
        // Rechercher des voitures par critères
        public function rechercherVoitures(Request $request)
        {
            $query = Voiture::query();
    
            if ($request->has('prix')) {
                $query->where('prix', '<=', $request->input('prix'));
            }
            if ($request->has('marque')) {
                $query->whereHas('marque', function ($q) use ($request) {
                    $q->where('nom', $request->input('marque'));
                });
            }
            if ($request->has('categorie')) {
                $query->whereHas('categorie', function ($q) use ($request) {
                    $q->where('nom', $request->input('categorie'));
                });
            }
    
            return response()->json($query->get());
        }
    
        // Voir les détails d'une voiture
        public function voirDetailVoiture($id)
        {
            $voiture = Voiture::with(['marque', 'categorie'])->findOrFail($id);
            return response()->json($voiture);
        }
    
}
