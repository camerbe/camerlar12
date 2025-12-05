<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $secretKey = config('services.recaptcha.secret');
        // 2. Requête POST à l'API de vérification de Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $value, // $value est le jeton reCAPTCHA reçu du formulaire
            // 'remoteip' => request()->ip(), // Facultatif : envoi de l'IP de l'utilisateur
        ]);

        // 3. Analyse de la réponse JSON
        $body = $response->json();

        // 4. Vérification du succès
        if (! $body['success']) {
            // Si la vérification échoue
            $fail('La vérification reCAPTCHA a échoué. Veuillez réessayer.');
        }
    }
}
