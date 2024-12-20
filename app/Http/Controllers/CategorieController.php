<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    //

     // Ajouter une catégorie
     public function create(Request $request)
     {
         $request->validate([
             'nom' => 'required|string|max:255',
             'description' => 'nullable|string|max:1000',
         ]);
 
         $categorie = new Categorie();
         $categorie->nom = $request->nom;
         $categorie->description = $request->description;
         $categorie->status = 'active'; // Par défaut, la catégorie est active
         $categorie->save();
 
         return response()->json(['message' => 'Catégorie ajoutée avec succès.']);
     }
 
     // Consulter la liste des catégories
     public function index()
     {
         $categories = Categorie::all();
         return response()->json($categories);
     }
 
     // Activer une catégorie
     public function activate($id)
     {
         $categorie = Categorie::findOrFail($id);
         $categorie->status = 'active';
         $categorie->save();
 
         return response()->json(['message' => 'Catégorie activée avec succès.']);
     }
 
     // Désactiver une catégorie
     public function deactivate($id)
     {
         $categorie = Categorie::findOrFail($id);
         $categorie->status = 'inactive';
         $categorie->save();
 
         return response()->json(['message' => 'Catégorie désactivée avec succès.']);
     }
 
     // Modifier une catégorie
     public function update(Request $request, $id)
     {
         $categorie = Categorie::findOrFail($id);
 
         $request->validate([
             'nom' => 'required|string|max:255',
             'description' => 'nullable|string|max:1000',
         ]);
 
         $categorie->nom = $request->nom;
         $categorie->description = $request->description;
         $categorie->save();
 
         return response()->json(['message' => 'Catégorie modifiée avec succès.']);
     }
}
