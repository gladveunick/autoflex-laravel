<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use Paydunya\Setup;
use Paydunya\Checkout\Store;
use Paydunya\Checkout\Invoice;

class PaiementController extends Controller
{
    //
    public function __construct()
    {
        // Configuration PayDunya
        Setup::setMasterKey(config('services.paydunya.master_key'));
        Setup::setPublicKey(config('services.paydunya.public_key'));
        Setup::setPrivateKey(config('services.paydunya.private_key'));
        Setup::setToken(config('services.paydunya.token'));
        Setup::setMode('test'); // Utilise 'test' pour le mode de test

        Store::setName("Application de Location");
        Store::setTagline("Réservez vos voitures en toute simplicité");
        Store::setPhoneNumber("243812345678");
        Store::setPostalAddress("Brazzaville, République du Congo");
    }

    // Paiement via carte ou mobile money
    public function payer(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'montant' => 'required|numeric|min:0',
        ]);

        $invoice = new Invoice();

        // Ajout des détails de la transaction
        $invoice->addItem("Paiement de réservation", 1, $request->montant, $request->montant);
        $invoice->setTotalAmount($request->montant);

        // Callback pour le paiement
        $invoice->setCallbackUrl(route('paiement.callback'));

        if ($invoice->create()) {
            // Enregistrement du paiement avec statut en attente
            $paiement = Paiement::create([
                'reservation_id' => $request->reservation_id,
                'locataire_id' => auth()->id(),
                'mode_paiement' => 'paydunya',
                'montant' => $request->montant,
                'statut' => 'en attente',
                'reference_paiement' => $invoice->getToken(),
            ]);

            return response()->json(['payment_link' => $invoice->getInvoiceUrl(), 'paiement' => $paiement], 200);
        } else {
            return response()->json(['message' => 'Erreur de paiement : ' . $invoice->response_text], 500);
        }
    }

    // Callback après le paiement
    public function callback(Request $request)
    {
        $token = $request->input('token');

        $invoice = new Invoice();
        if ($invoice->confirm($token)) {
            $paiement = Paiement::where('reference_paiement', $token)->first();

            if ($invoice->getStatus() == "completed" && $paiement) {
                $paiement->update(['statut' => 'réussi']);
                return response()->json(['message' => 'Paiement réussi']);
            } else {
                $paiement->update(['statut' => 'échoué']);
                return response()->json(['message' => 'Paiement échoué']);
            }
        } else {
            return response()->json(['message' => 'Erreur de confirmation : ' . $invoice->response_text], 500);
        }
    }

}
