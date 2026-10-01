<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Flux\Flux;
use Illuminate\Support\Facades\RateLimiter;

new class extends Component
{
    public $heroArticle;
    public $sopie = null;
    public $debat = null;
    public $droit = null;
    public $camer = null;
    public $skypper = null;
    //
    #[Validate('required',message: "Le nom est requis.")]
    #[Validate('min:2',message: "Le nom doit avoir au moins 2 caractères.")]
    #[Validate('max:100',message: "Le nom doit avoir au plus 100 caractères.")]
    public string $nom = '';

    #[Validate('required',message: "Le prénom est requis.")]
    #[Validate('min:2',message: "Le prénom doit avoir au moins 2 caractères.")]
    #[Validate('max:100',message: "Le prénom doit avoir au plus 100 caractères.")]
    public string $prenom = '';

    #[Validate('required',message: "Le mail est requis.")]
    #[Validate('email:rfc,dns',message: "L’adresse e-mail n’est pas valide.")]
    public string $email = '';

    #[Validate('required',message: "L'objet est requis.")]
    #[Validate('min:3',message: "L'objet doit avoir au moins 3 caractères.")]
    #[Validate('max:150',message: "L'objet doit avoir au plus 150 caractères.")]
    public string $objet = '';

    #[Validate('required',message: "Le message est requis.")]
    #[Validate('min:10',message: "Le message doit avoir au moins 10 caractères.")]
    #[Validate('max:5000',message: "Le message doit avoir au plus 5000 caractères.")]
    public string $message = '';

    #[Validate('required|string',message: "La validation reCAPTCHA est obligatoire..")]
    public string $recaptchaToken = '';

    public string $website = '';

    public bool $sent = false;

    public function send(): void{

        if (!empty($this->website)) {
            // On simule un succès pour tromper le bot
            $this->resetForm();
            return;
        }
        //dd($this->objet);
        $rateLimitKey = 'contact-form:' . request()->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            Flux::toast(
                heading: 'Message',
                text: "Trop de tentatives d'envoi. Veuillez patienter {$seconds} secondes avant de réessayer.",
                variant: 'danger',
            );
            throw ValidationException::withMessages([
                'email' => "Trop de tentatives d'envoi. Veuillez patienter {$seconds} secondes avant de réessayer.",

            ]);

        }

        RateLimiter::hit($rateLimitKey, 60);

        $this->validate();

        $this->validateRecaptcha();
        Mail::to(config('mail.from.address'))
            ->send(new ContactMail([
                    'nom'=> $this->nom,
                    'prenom'=> $this->prenom,
                    'email'=> $this->email,
                    'objet'=> $this->objet,
                    'message'=> $this->message,
                ]));
        $this->reset([
            'nom',
            'prenom',
            'email',
            'objet',
            'message',
            'recaptchaToken',
        ]);
        $this->sent = true;
        $this->dispatch('contact-sent');
        Flux::toast(
            heading: 'Message',
            text: 'Votre message a bien été envoyé. Merci de nous avoir contactés.',
            variant: 'success',
        );
    }
    private function resetForm(): void
    {
        $this->reset([
            'nom',
            'prenom',
            'email',
            'objet',
            'message',
            'recaptchaToken',
            'website',
        ]);
    }
    private function validateRecaptcha(): void
    {

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => config('analytics.recaptcha-localhost-secret'),
                'response' => $this->recaptchaToken,
                'remoteip' => request()->ip(),
            ]
        );

        $result = $response->json();
        //dd($result);
        $success = $result['success'] ?? false;

        $score = $result['score'] ?? 0;

        $action = $result['action'] ?? null;

        if (
            !$success ||
            $score < 0.5 ||
            $action !== 'contact'
        ) {
            throw ValidationException::withMessages([
                'recaptchaToken' => 'La vérification anti-robot a échoué. Veuillez réessayer.',
            ]);
        }
    }
};
?>

<div>
    <div class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8 text-justify">
                <flux:card class="space-y-6 w-full  mx-auto">
                    <div class="flex items-center gap-3 border-b-5 border-red-500 pb-5 mb-3">
                        <flux:icon name="home" class="size-5" />
                        <flux:heading size="lg" class="font-bold  uppercase">
                            Contact
                        </flux:heading>

                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5">
                        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Allemagne</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    +49 176 21 96 12 84
                                </p>
                            </div>

                        </div>

                        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Belgique</p>
                                <p class="text-xs text-gray-500 mt-1">+32 475 49 20 69</p>
                                <p class="text-xs text-gray-500 mt-1">+32 485 39 58 85</p>
                                <p class="text-xs text-gray-500 mt-1">+32 484 90 52 54</p>
                            </div>

                        </div>

                        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Cameroun</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                    +237 95 23 76 38
                                </p>
                            </div>

                        </div>

                        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">France</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                    +33 651 86 05 81
                                </p>
                            </div>

                        </div>
                    </div>

                    <div class="flex items-center gap-3 border-b-5 pb-3 border-green-500">
                        <flux:icon name="envelope" class="size-5" />
                        <flux:heading size="lg" class="font-bold  uppercase">
                            Nous contacter
                        </flux:heading>
                    </div>

                    <form id="contactForm" wire:submit.prevent="send">
                        @csrf

                        <!-- CHAMP HONEYPOT (Invisible pour les humains, rempli par les bots) -->
                        <div class="hidden" aria-hidden="true">
                            <label for="website">Site web (ne pas remplir)</label>
                            <input type="text" id="website" wire:model="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="space-y-6">
                            <div class="flex flex-row gap-4">
                                <div class="flex-1">
                                    <flux:input
                                        wire:model="nom"
                                        type="text"
                                        min="1"
                                        label="Nom" size="xm" placeholder="Nom ..." />
                                </div>
                                <div class="flex-1">
                                    <flux:input
                                        wire:model="prenom"
                                        type="text"
                                        min="1"
                                        label="Prénom" size="xm" placeholder="Prénom ..." />
                                </div>
                            </div>
                            <flux:input
                                wire:model="email"
                                type="email"
                                min="1"
                                label="Email" size="xm" placeholder="Email ..." />
                            <flux:input
                                wire:model="objet"
                                type="text"
                                min="1"
                                label="Objet" size="xm" placeholder="Objet ..." />
                            <flux:textarea
                                wire:model="message"
                                label="Message"
                                placeholder="Message..."
                            />
                            <!-- Bouton de soumission avec temporisation / état de chargement -->
                            <div class="pt-2 flex justify-center">
                                <flux:button
                                    type="submit"
                                    variant="primary"
                                    class="w-1/2"
                                    wire:loading.attr="disabled"
                                    wire:target="send">

                                    <span wire:loading.remove wire:target="send">Envoyer le message</span>
                                    <span wire:loading wire:target="send" class="flex items-center justify-center gap-2">
                                        <flux:icon name="arrow-path" class="size-4 animate-spin" />
                                        Envoi en cours...
                                    </span>
                                </flux:button>
                            </div>
                        </div>

                    </form>
                </flux:card>
            </div>
            <aside class="lg:col-span-4 space-y-6">
                {{-- 4. Sous-composant Livewire pour la Sidebar--}}

                <livewire:video :camer="null" :sopie="$sopie" />
                <livewire:debat :debat="$debat"/>
                <livewire:pub-iframe :iframe="$iframe"/>
                <livewire:droit :droit="$droit"/>
                <livewire:video :camer="$camer" :sopie="null" />
                <livewire:skypper :skypper="$skypper" />


            </aside>
        </div>
    </div>


</div>
<!-- SCRITPS RECAPTCHA V3 -->
@push('scripts')
    <!-- Remplacez VOTRE_SITE_KEY par votre clé publique Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render="{{config('analytics.recaptcha-localhost-site')}}"></script>

        <!-- 1. Le script officiel de Google que vous venez de copier -->
        <script>
            (function(){var w=window,C='___grecaptcha_cfg',cfg=w[C]=w[C]||{},N='grecaptcha';var gr=w[N]=w[N]||{};gr.ready=gr.ready||function(f){(cfg['fns']=cfg['fns']||[]).push(f);};w['__recaptcha_api']='https://www.google.com/recaptcha/api2/';(cfg['render']=cfg['render']||[]).push('6LcXp6IZAAAAAKs2K91XqLDsrAP1602y8UKPgRmV');(cfg['anchor-ms']=cfg['anchor-ms']||[]).push(20000);(cfg['execute-ms']=cfg['execute-ms']||[]).push(30000);w['__google_recaptcha_client']=true;var d=document,po=d.createElement('script');po.type='text/javascript';po.async=true; po.charset='utf-8';po.src='https://www.gstatic.com/recaptcha/releases/zqB-6Xpbd3lCIvi7Tr2D0pob/recaptcha__fr.js';po.crossOrigin='anonymous';po.integrity='sha384-9rprEH/T8Fk8sH+D7pBenb47bRsN+cmN0ibjWW+OOyY4Obe9xi37s76BUvCe4paT';var e=d.querySelector('script[nonce]'),n=e&&(e['nonce']||e.getAttribute('nonce'));if(n){po.setAttribute('nonce',n);}var s=d.getElementsByTagName('script')[0];s.parentNode.insertBefore(po, s);})();
        </script>

        <!-- 2. La logique pour intercepter la soumission et l'envoyer à Livewire -->
        <script>
            document.getElementById('contactForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Empêche l'envoi direct par défaut

                grecaptcha.ready(function() {
                    // Exécution de reCAPTCHA v3 avec l'action 'contact'
                    grecaptcha.execute('{{config('analytics.recaptcha-localhost-site')}}', { action: 'contact' }).then(function(token) {
                        // On transmet le jeton à la propriété Livewire 'recaptchaToken'
                    @this.set('recaptchaToken', token).then(() => {
                        // Une fois enregistré, on déclenche la méthode PHP 'send'
                    @this.call('send');
                    });
                    });
                });
            });
        </script>
@endpush
