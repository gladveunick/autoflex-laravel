<?php

namespace App\Http\Controllers;

use App\Models\Favori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    //

        // Ajouter une voiture en favori
        public function ajouterFavori(Request $request)
        {
            $request->validate(['voiture_id' => 'required|exists:voitures,id']);
    
            $favori = Favori::create([
                'locataire_id' => Auth::id(),
                'voiture_id' => $request->voiture_id,
            ]);
    
            return response()->json($favori);
        }
    
        // Supprimer une voiture des favoris
        public function supprimerFavori($id)
        {
            $favori = Favori::where('id', $id)->where('locataire_id', Auth::id())->firstOrFail();
            $favori->delete();
    
            return response()->json(['message' => 'Voiture retirée des favoris avec succès.']);
        }
    
        // Lister les favoris
        public function listerFavoris()
        {
            $favoris = Favori::with('voiture')->where('locataire_id', Auth::id())->get();
            return response()->json($favoris);
        }
}
