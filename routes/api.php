<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\ModeleController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\VoitureController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaiementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Callback pour le paiement
Route::get('/paiements/callback', [PaiementController::class, 'callback'])->name('paiement.callback');
    

// Routes protégées avec middleware 'auth:sanctum'
Route::middleware('auth:sanctum')->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profil utilisateur
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/profile/deactivate', [AuthController::class, 'deactivateAccount']);

    // Demande de promotion au rôle de propriétaire
    Route::post('/request-promotion', [AuthController::class, 'requestPromotion']);

    // Recherche de voitures
    Route::get('/voitures/recherche', [LocataireController::class, 'rechercherVoitures']);

    // Visualiser les détails d'une voiture
    Route::get('/voitures/{id}', [LocataireController::class, 'voirDetailVoiture']);

    // Réserver une voiture
    Route::post('/reservations', [ReservationController::class, 'reserverVoiture']);

    // Consulter l'historique des réservations
    Route::get('/reservations/historique', [ReservationController::class, 'historiqueReservations']);

    // Annuler une réservation
    Route::put('/reservations/{id}/annuler', [ReservationController::class, 'annulerReservation']);

    // Laisser un avis
    Route::post('/avis', [AvisController::class, 'laisserAvis']);

    // Supprimer un avis
    Route::delete('/avis/{id}', [AvisController::class, 'supprimerAvis']);

    // Mettre une voiture en favori
    Route::post('/favoris', [FavoriController::class, 'ajouterFavori']);

    // Retirer une voiture des favoris
    Route::delete('/favoris/{id}', [FavoriController::class, 'supprimerFavori']);

    // Consulter les favoris
    Route::get('/favoris', [FavoriController::class, 'listerFavoris']);

    // Consulter la liste des propriétaires
    Route::get('/proprietaires', [ProprietaireController::class, 'listerProprietaires']);

    // Paiement via PayDunya (carte bancaire et Mobile Money)
    Route::post('/paiements', [PaiementController::class, 'payer']);



        // Routes réservées aux propriétaires
        // Route::middleware('can:proprietaire')->group(function () {
            // Gestion des voitures
            Route::post('/voiture', [VoitureController::class, 'create']); // Ajouter une voiture
            Route::get('/voitures', [VoitureController::class, 'index']); // Consulter la liste des voitures
            Route::put('/voiture/{id}/activate', [VoitureController::class, 'activate']); // Activer une voiture
            Route::put('/voiture/{id}/deactivate', [VoitureController::class, 'deactivate']); // Désactiver une voiture
            Route::put('/voiture/{id}', [VoitureController::class, 'update']); // Modifier une voiture
    
            // Gestion des réservations
            Route::get('/reservations', [ReservationController::class, 'index']); // Consulter les réservations
            Route::put('/reservation/{id}/validate', [ReservationController::class, 'validateReservation']); // Valider une réservation
            Route::put('/reservation/{id}/reject', [ReservationController::class, 'rejectReservation']); // Rejeter une réservation
            Route::put('/reservation/{id}/cancel', [ReservationController::class, 'cancelReservation']); // Annuler une réservation
    
            // Gestion des avis clients
            Route::get('/avis', [AvisController::class, 'index']); // Consulter les avis clients
            Route::put('/avis/{id}/accept', [AvisController::class, 'accept']); // Accepter un avis
            Route::put('/avis/{id}/reject', [AvisController::class, 'reject']); // Rejeter un avis
            Route::post('/avis/{id}/respond', [AvisController::class, 'respond']); // Répondre à un avis

            Route::get('/categories', [CategorieController::class, 'index']); // Consulter la liste des catégories

            Route::get('/marques', [MarqueController::class, 'index']); // Consulter la liste des marques
            Route::get('/modeles', [ModeleController::class, 'index']); // Consulter la liste des modèles
            Route::get('/voitures', [VoitureController::class, 'index']); // Consulter la liste des voitures

        // });
    
    

    // Routes réservées aux administrateurs
    // Route::middleware('can:admin')->group(function () {
        Route::put('/validate-owner/{id}/accept', [AuthController::class, 'acceptOwner']);
        Route::put('/validate-owner/{id}/reject', [AuthController::class, 'rejectOwner']);
        Route::put('/activate-account/{id}', [AuthController::class, 'activateAccount']);
        Route::put('/deactivate-account/{id}', [AuthController::class, 'deactivateAccountByAdmin']);

        // Gestion des catégories
        Route::post('/categorie', [CategorieController::class, 'create']); // Ajouter une catégorie
        Route::get('/categories', [CategorieController::class, 'index']); // Consulter la liste des catégories
        Route::put('/categorie/{id}/activate', [CategorieController::class, 'activate']); // Activer une catégorie
        Route::put('/categorie/{id}/deactivate', [CategorieController::class, 'deactivate']); // Désactiver une catégorie
        Route::put('/categorie/{id}', [CategorieController::class, 'update']); // Modifier une catégorie

        // Gestion des marques
        Route::post('/marque', [MarqueController::class, 'create']); // Ajouter une marque
        Route::get('/marques', [MarqueController::class, 'index']); // Consulter la liste des marques
        Route::put('/marque/{id}/activate', [MarqueController::class, 'activate']); // Activer une marque
        Route::put('/marque/{id}/deactivate', [MarqueController::class, 'deactivate']); // Désactiver une marque
        Route::put('/marque/{id}', [MarqueController::class, 'update']); // Modifier une marque

        // Gestion des modèles
        Route::post('/modele', [ModeleController::class, 'create']); // Ajouter un modèle
        Route::get('/modeles', [ModeleController::class, 'index']); // Consulter la liste des modèles
        Route::put('/modele/{id}/activate', [ModeleController::class, 'activate']); // Activer un modèle
        Route::put('/modele/{id}/deactivate', [ModeleController::class, 'deactivate']); // Désactiver un modèle
        Route::put('/modele/{id}', [ModeleController::class, 'update']); // Modifier un modèle

        // Gestion des comptes
        Route::get('/locataires', [AuthController::class, 'listLocataires']); // Liste des locataires
        Route::get('/proprietaires', [AuthController::class, 'listProprietaires']); // Liste des propriétaires

        // Gestion des réservations
        Route::get('/reservations', [ReservationController::class, 'listAllReservations']); // Liste des réservations

        // Notifications push
        Route::post('/notifications', [NotificationController::class, 'sendPushNotification']); // Envoyer une notification push

        // Gestion des avis
        Route::put('/avis/{id}/accept', [AvisController::class, 'acceptAvis']); // Accepter un avis
        Route::put('/avis/{id}/reject', [AvisController::class, 'rejectAvis']); // Rejeter un avis

    // });
});
