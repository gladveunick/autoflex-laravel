<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voiture;

class VoitureController extends Controller
{
    //

    public function create(Request $request)
    {
        $data = $request->validate([
            'immatriculation' => 'required|string',
            'image' => 'required|image',
            'annee' => 'required|integer',
            'nombre_sieges' => 'required|integer',
            'type_carburant' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'marque_id' => 'required|exists:marques,id',
        ]);

        $data['proprietaire_id'] = Auth::id();
        Voiture::create($data);

        return response()->json(['message' => 'Voiture ajoutée avec succès']);
    }

    public function index()
    {
        $voitures = Voiture::where('proprietaire_id', Auth::id())->get();
        return response()->json($voitures);
    }

    public function activate($id)
    {
        $voiture = Voiture::where('id', $id)->where('proprietaire_id', Auth::id())->firstOrFail();
        $voiture->status = 'active';
        $voiture->save();

        return response()->json(['message' => 'Voiture activée']);
    }

    public function deactivate($id)
    {
        $voiture = Voiture::where('id', $id)->where('proprietaire_id', Auth::id())->firstOrFail();
        $voiture->status = 'inactive';
        $voiture->save();

        return response()->json(['message' => 'Voiture désactivée']);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'immatriculation' => 'string',
            'image' => 'image',
            'annee' => 'integer',
            'nombre_sieges' => 'integer',
            'type_carburant' => 'string',
            'categorie_id' => 'exists:categories,id',
            'marque_id' => 'exists:marques,id',
        ]);

        $voiture = Voiture::where('id', $id)->where('proprietaire_id', Auth::id())->firstOrFail();
        $voiture->update($data);

        return response()->json(['message' => 'Voiture mise à jour']);
    }
}
