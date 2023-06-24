<?php

namespace App\Http\Controllers\email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class sendEmail extends Controller
{

    public function sendEmail(Request $request)
    {
        $mail_data = [
            'recipient' => $request->recipient,
            'fromEmail' => $request->fromEmail,
            "fromName" => $request->fromName,
            "subject" => "Email de confirmação Afilidrop"
        ];

        Mail::send('emails.welcome', $mail_data, function ($message) use ($mail_data){
            $message->from('placasgames16@gmail.com', "Afilidrop");
            $message->to($mail_data['fromEmail'])->subject("Sua conta foi criada com Sucesso! Começe Agora a Se Afiliar.");
        });
    }
}
