<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    public function sendPushNotification(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'message' => 'required|string',
    ]);

    // Exemple de logique pour envoyer une notification
    $notification = [
        'title' => $request->title,
        'message' => $request->message,
    ];

    // Ici, tu pourrais intégrer un service de notification comme Firebase Cloud Messaging (FCM)

    return response()->json(['success' => 'Notification envoyée avec succès', 'notification' => $notification], 200);
}

}
