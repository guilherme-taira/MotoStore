<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\DatabaseNotification;

class notificationController extends Controller
{
    public function readNotification(Request $request){
        auth()->user()->unreadNotifications->where('id', $request->id)->markAsRead();
    }
}
