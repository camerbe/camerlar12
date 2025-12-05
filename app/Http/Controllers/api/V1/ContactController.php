<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
//use App\Http\Controllers\Request;
use App\Mail\ContactMail;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    //
    public function submit(Request $request)
    {
        // 1. Validation de tous les champs, y compris reCAPTCHA
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            // Le champ `recaptchaReactive` doit contenir le jeton du client
            'recaptchaReactive' => ['required', new Recaptcha],
        ]);

        if($validatedData){
            Mail::to(env('MAIL_USERNAME'))->send( new ContactMail($validatedData));
            return response()->json([
                'success'=>true,
                'message' => 'Merci pour votre message, nous vous répondrons bientôt.'
            ]);
        }
        return response()->json([
            'success'=>false,
            'message' => 'Echec'
        ]);

    }
}
