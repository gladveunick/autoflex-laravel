<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProprietaireController extends Controller
{
    //
        // Lister les propriétaires
        public function listerProprietaires()
        {
            $proprietaires = User::where('role', 'proprietaire')->get();
            return response()->json($proprietaires);
        }
}
