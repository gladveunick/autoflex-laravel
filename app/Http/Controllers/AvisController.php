<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    //

    public function index()
{
    try {
        $user = auth()->user();
        $avis = Avis::with(['locataire', 'voiture'])
            ->when($user->role === 'proprietaire', function ($query) use ($user) {
                // Si l'utilisateur est un propriétaire, ne montrer que les avis de ses voitures
                return $query->whereHas('voiture', function ($q) use ($user) {
                    $q->where('proprietaire_id', $user->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($avis) {
                return [
                    'id' => $avis->id,
                    'user_name' => $avis->locataire->name,
                    'comment' => $avis->commentaire,
                    'rating' => 5, // À adapter selon votre logique de notation
                    'created_at' => $avis->created_at,
                    'status' => $avis->statut,
                    'response' => $avis->reponse,
                    'voiture' => [
                        'id' => $avis->voiture->id,
                        'marque' => $avis->voiture->marque->nom,
                        'modele' => $avis->voiture->modele->nom,
                    ]
                ];
            });

        return response()->json($avis);
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la récupération des avis: ' . $e->getMessage());
        return response()->json([
            'message' => 'Une erreur est survenue lors de la récupération des avis',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function accept($id)
    {
        $avis = Avis::whereHas('voiture', function ($query) {
            $query->where('proprietaire_id', Auth::id());
        })->findOrFail($id);

        $avis->statut = 'accepté';
        $avis->save();

        return response()->json(['message' => 'Avis accepté']);
    }

    public function reject($id)
    {
        $avis = Avis::whereHas('voiture', function ($query) {
            $query->where('proprietaire_id', Auth::id());
        })->findOrFail($id);

        $avis->statut = 'rejeté';
        $avis->save();

        return response()->json(['message' => 'Avis rejeté']);
    }

    public function repondre(Request $request, $id)
    {
        $request->validate([
            'reponse' => 'required|string',
        ]);

        $avis = Avis::whereHas('voiture', function ($query) {
            $query->where('proprietaire_id', Auth::id());
        })->findOrFail($id);

        $avis->reponse = $request->reponse;
        $avis->save();

        return response()->json(['message' => 'Réponse ajoutée à l\'avis']);
    }


    // Accepter un avis
public function acceptAvis($id)
{
    $avis = Avis::findOrFail($id);
    $avis->statut = 'accepté';
    $avis->save();

    return response()->json(['success' => 'Avis accepté avec succès', 'avis' => $avis], 200);
}

// Rejeter un avis
public function rejectAvis($id)
{
    $avis = Avis::findOrFail($id);
    $avis->statut = 'rejeté';
    $avis->save();

    return response()->json(['success' => 'Avis rejeté avec succès', 'avis' => $avis], 200);
}

// section locataire

    // Laisser un avis
    public function laisserAvis(Request $request)
    {
        $request->validate([
            'voiture_id' => 'required|exists:voitures,id',
            'commentaire' => 'required|string',
        ]);

        $avis = Avis::create([
            'voiture_id' => $request->voiture_id,
            'locataire_id' => Auth::id(),
            'commentaire' => $request->commentaire,
            'statut' => 'en attente',
        ]);

        return response()->json($avis);
    }

    // Supprimer un avis
    public function supprimerAvis($id)
    {
        $avis = Avis::where('id', $id)->where('locataire_id', Auth::id())->firstOrFail();
        $avis->delete();

        return response()->json(['message' => 'Avis supprimé avec succès.']);
    }


}
