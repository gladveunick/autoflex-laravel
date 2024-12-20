<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marque;

class MarqueController extends Controller
{
    //

        // Ajouter une marque
        public function create(Request $request)
        {
            $request->validate([
                'nom' => 'required|string|max:255',
                'logo' => 'required|string|max:255', // URL du logo
                'pays_dorigine' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
            ]);
    
            $marque = new Marque();
            $marque->nom = $request->nom;
            $marque->logo = $request->logo;
            $marque->pays_dorigine = $request->pays_dorigine;
            $marque->description = $request->description;
            $marque->status = 'active'; // Par défaut, la marque est active
            $marque->save();
    
            return response()->json(['message' => 'Marque ajoutée avec succès.']);
        }
    
        // Consulter la liste des marques
        public function index()
        {
            $marques = Marque::all();
            return response()->json($marques);
        }
    
        // Activer une marque
        public function activate($id)
        {
            $marque = Marque::findOrFail($id);
            $marque->status = 'active';
            $marque->save();
    
            return response()->json(['message' => 'Marque activée avec succès.']);
        }
    
        // Désactiver une marque
        public function deactivate($id)
        {
            $marque = Marque::findOrFail($id);
            $marque->status = 'inactive';
            $marque->save();
    
            return response()->json(['message' => 'Marque désactivée avec succès.']);
        }
    
        // Modifier une marque
        public function update(Request $request, $id)
        {
            $marque = Marque::findOrFail($id);
    
            $request->validate([
                'nom' => 'required|string|max:255',
                'logo' => 'required|string|max:255', // URL du logo
                'pays_dorigine' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
            ]);
    
            $marque->nom = $request->nom;
            $marque->logo = $request->logo;
            $marque->pays_dorigine = $request->pays_dorigine;
            $marque->description = $request->description;
            $marque->save();
    
            return response()->json(['message' => 'Marque modifiée avec succès.']);
        }
    
}
