<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modele;


class ModeleController extends Controller
{
    //

        // Ajouter un modèle
        public function create(Request $request)
        {
            $request->validate([
                'marque_id' => 'required|exists:marques,id',
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
            ]);
    
            $modele = new Modele();
            $modele->marque_id = $request->marque_id;
            $modele->nom = $request->nom;
            $modele->description = $request->description;
            $modele->status = 'active'; // Par défaut, le modèle est actif
            $modele->save();
    
            return response()->json(['message' => 'Modèle ajouté avec succès.']);
        }
    
        // Consulter la liste des modèles
        public function index()
        {
            $modeles = Modele::all();
            return response()->json($modeles);
        }
    
        // Activer un modèle
        public function activate($id)
        {
            $modele = Modele::findOrFail($id);
            $modele->status = 'active';
            $modele->save();
    
            return response()->json(['message' => 'Modèle activé avec succès.']);
        }
    
        // Désactiver un modèle
        public function deactivate($id)
        {
            $modele = Modele::findOrFail($id);
            $modele->status = 'inactive';
            $modele->save();
    
            return response()->json(['message' => 'Modèle désactivé avec succès.']);
        }
    
        // Modifier un modèle
        public function update(Request $request, $id)
        {
            $modele = Modele::findOrFail($id);
    
            $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
            ]);
    
            $modele->nom = $request->nom;
            $modele->description = $request->description;
            $modele->save();
    
            return response()->json(['message' => 'Modèle modifié avec succès.']);
        }
}
