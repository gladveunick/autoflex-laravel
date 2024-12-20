<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //  Inscription
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string|in:locataire,proprietaire,admin',
            'company_name' => 'required_if:role,proprietaire|string|max:255',
            'location' => 'required_if:role,proprietaire|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required_if:role,proprietaire|string|max:1000',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;

        if ($request->role === 'proprietaire') {
            $user->company_name = $request->company_name;
            $user->location = $request->location;
            $user->description = $request->description;
            $user->status = 'pending';

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                $user->logo = $path;
            }
        } else {
            $user->status = 'approved';
        }

        $user->save();

        return response()->json(['message' => 'Inscription réussie.']);
    }

    //  Connexion
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Email ou mot de passe incorrect.'], 401);
        }

        $user = Auth::user();

        if ($user->role === 'proprietaire' && $user->status === 'pending') {
            return response()->json(['message' => 'Votre compte est en attente de validation par un administrateur.'], 403);
        }

        if ($user->status === 'rejected') {
            return response()->json(['message' => 'Votre compte a été rejeté par un administrateur.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'role' => $user->role,  'user' => $user]);
    }

    // Déconnexion
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Déconnexion réussie.']);
    }

    // Voir le profil
    public function profile()
    {
        return response()->json(Auth::user());
    }

    // Mettre à jour le profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'string|max:255',
            'company_name' => 'string|max:255|nullable',
            'location' => 'string|max:255|nullable',
            'description' => 'string|max:1000|nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->fill($data);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $user->logo = $path;
        }

        $user->save();

        return response()->json(['message' => 'Profil mis à jour avec succès.', 'user' => $user]);
    }

    // Désactiver le compte
    public function deactivateAccount()
    {
        $user = Auth::user();
        $user->status = 'deactivated';
        $user->save();

        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'Compte désactivé avec succès.']);
    }

    // Demander une promotion au rôle de propriétaire
    public function requestPromotion(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $user->role = 'proprietaire';
        $user->company_name = $request->company_name;
        $user->location = $request->location;
        $user->description = $request->description;
        $user->status = 'pending';

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $user->logo = $path;
        }

        $user->save();

        return response()->json(['message' => 'Votre demande de promotion a été envoyée.']);
    }

 // Accepter une demande de propriétaire
 public function acceptOwner($id)
 {
     $user = User::findOrFail($id);

     if ($user->role === 'proprietaire' && $user->status === 'pending') {
         $user->status = 'active';
         $user->save();

         return response()->json(['message' => 'Compte propriétaire accepté avec succès.']);
     }

     return response()->json(['message' => 'Action non autorisée ou utilisateur invalide.'], 400);
 }

 // Rejeter une demande de propriétaire
 public function rejectOwner($id)
 {
     $user = User::findOrFail($id);

     if ($user->role === 'proprietaire' && $user->status === 'pending') {
         $user->status = 'rejected';
         $user->save();

         return response()->json(['message' => 'Compte propriétaire rejeté avec succès.']);
     }

     return response()->json(['message' => 'Action non autorisée ou utilisateur invalide.'], 400);
 }


 // Activer un compte utilisateur
public function activateAccount($id)
{
    $user = User::findOrFail($id);

    // Vérifier si l'utilisateur a un statut 'désactivé'
    if ($user->status === 'deactivated') {
        $user->status = 'active';
        $user->save();

        return response()->json(['message' => 'Le compte utilisateur a été activé.']);
    }

    return response()->json(['message' => 'Ce compte est déjà actif ou dans un autre statut.'], 400);
}

// Désactiver un compte utilisateur
public function deactivateAccountByAdmin($id)
{
    $user = User::findOrFail($id);

    // Vérifier si l'utilisateur a un statut 'actif'
    if ($user->status === 'active') {
        $user->status = 'deactivated';
        $user->save();

        return response()->json(['message' => 'Le compte utilisateur a été désactivé.']);
    }

    return response()->json(['message' => 'Ce compte est déjà désactivé ou dans un autre statut.'], 400);
}

// Liste des locataires
public function listLocataires()
{
    $locataires = User::where('role', 'locataire')->get();
    return response()->json($locataires, 200);
}

// Liste des propriétaires
public function listProprietaires()
{
    $proprietaires = User::where('role', 'proprietaire')->get();
    return response()->json($proprietaires, 200);
}


}
