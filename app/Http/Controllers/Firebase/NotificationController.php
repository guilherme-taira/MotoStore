<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function getUserNotificationTokens($userId)
    {
        // Suponha que você receba o userId via request ou esteja autenticado
        $tokens = $this->firebaseService->getUserTokens($userId);

        return response()->json([
            'user_id' => $userId,
            'tokens' => $tokens,
        ]);
    }
}
