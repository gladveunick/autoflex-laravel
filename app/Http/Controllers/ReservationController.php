<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReservationController extends Controller
{
    //

    public function index()
    {
        // Consulter les réservations des voitures du propriétaire connecté
        $reservations = Reservation::whereHas('voiture', function ($query) {
            $query->where('proprietaire_id', Auth::id());
        })->get();

        return response()->json($reservations);
    }

    public function accept($id)
    {
        $reservation = Reservation::whereHas('voiture', function ($query) {
            $query->where('proprietaire_id', Auth::id());
        })->findOrFail($id);

        $reservation->statut = 'validée';
        $reservation->save();

        return response()->json(['message' => 'Réservation validée']);
    }

    public function reject($id)
    {
        $reservation = Reservation::whereHas('voiture', function ($query) {
            $query->where('proprietaire_id', Auth::id());
        })->findOrFail($id);

        $reservation->statut = 'rejetée';
        $reservation->save();

        return response()->json(['message' => 'Réservation rejetée']);
    }

    public function cancel($id)
    {
        $reservation = Reservation::whereHas('voiture', function ($query) {
            $query->where('proprietaire_id', Auth::id());
        })->findOrFail($id);

        $reservation->statut = 'annulée';
        $reservation->save();

        return response()->json(['message' => 'Réservation annulée']);
    }


    public function listAllReservations()
{
    $reservations = Reservation::all();
    return response()->json($reservations, 200);
}


    // Réserver une voiture par lle locataire
    public function reserverVoiture(Request $request)
    {
        $request->validate([
            'voiture_id' => 'required|exists:voitures,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $voiture = Voiture::findOrFail($request->voiture_id);
        $nombre_jour = $request->date_debut->diffInDays($request->date_fin) + 1;
        $montant_total = $nombre_jour * $voiture->prix;

        $reservation = Reservation::create([
            'voiture_id' => $request->voiture_id,
            'locataire_id' => Auth::id(),
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'nombre_jour' => $nombre_jour,
            'montant_unitaire' => $voiture->prix,
            'montant_total' => $montant_total,
            'statut' => 'en attente',
        ]);

        return response()->json($reservation);
    }

    // Consulter l'historique des réservations par le locataire
    public function historiqueReservations()
    {
        $reservations = Reservation::where('locataire_id', Auth::id())->get();
        return response()->json($reservations);
    }

    // Annuler une réservation par le locataire
    public function annulerReservation($id)
    {
        $reservation = Reservation::where('id', $id)->where('locataire_id', Auth::id())->firstOrFail();
        $reservation->update(['statut' => 'annulée']);
        return response()->json(['message' => 'Réservation annulée avec succès.']);
    }


}
