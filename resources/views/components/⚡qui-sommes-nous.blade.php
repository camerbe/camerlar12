<?php

use Livewire\Component;

new class extends Component
{
    public $heroArticle;

    //
};
?>

<div>


    @php
        // === Informations légales à compléter ===
        $editeurNom = 'Camer group';
        $editeurAdresse = 'Camer.be';
        $editeurNumeroBCE = '0886.132.117';
        $emailContact = 'camer.be@gmail.com';

        $derniereMiseAJour = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
    @endphp


    <div class="space-y-8">
        @if($heroArticle)
            <livewire:featured-article :article="$heroArticle" />
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-8 space-y-8 text-justify">
                <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2">
                    <h2 class="text-xl font-extrabold font-heading uppercase tracking-wide">
                        Politique de confidentialité
                    </h2>
                    <span class="text-xs font-semibold text-brand-500">Dernière mise à jour : {{ $derniereMiseAJour }}</span>
                </div>

                <p class="small-text"></p>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">1. Qui sommes-nous</h2>
                    <p>
                        Camer.be est un portail d'actualités destiné à la diaspora camerounaise, édité par
                        <strong>{{ $editeurNom }}</strong>, dont le siège est établi à
                        <strong>{{ $editeurAdresse }}</strong>, immatriculé sous le numéro d'entreprise
                        <strong>{{ $editeurNumeroBCE }}</strong>.
                    </p>
                    <p>
                        Pour toute question relative à cette politique de confidentialité ou à vos données
                        personnelles, vous pouvez nous contacter à l'adresse :
                        <a href="mailto:{{ $emailContact }}">{{ $emailContact }}</a>.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">2. Quelles données nous collectons</h2>
                    <p>Selon votre usage du site, nous pouvons collecter :</p>
                    <ul>
                        <li>
                            <strong>Données de navigation</strong> : adresse IP, type de navigateur, pages
                            consultées, durée de visite, provenance (référent), collectées automatiquement
                            lors de votre visite
                        </li>
                        <li><strong>Cookies et technologies similaires</strong> : voir section 5</li>
                        <li>
                            <strong>Données que vous nous fournissez volontairement</strong> : si vous nous
                            contactez via un formulaire, laissez un commentaire, ou vous inscrivez à une
                            newsletter (nom, adresse e-mail, contenu du message)
                        </li>
                        <li>
                            <strong>Données publicitaires</strong> : via Google AdSense, des identifiants
                            publicitaires et des données de navigation peuvent être utilisés à des fins de
                            personnalisation des annonces, sous réserve de votre consentement
                        </li>
                    </ul>
                    <p>
                        Nous ne collectons aucune donnée sensible (origine, santé, opinions politiques ou
                        religieuses) dans le cadre normal d'utilisation du site.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">3. Finalités du traitement</h2>
                    <p>Vos données sont utilisées pour :</p>
                    <ul>
                        <li>Assurer le fonctionnement technique et la sécurité du site</li>
                        <li>Mesurer l'audience et améliorer notre contenu éditorial</li>
                        <li>
                            Diffuser des publicités, personnalisées ou non selon votre consentement, via
                            Google AdSense
                        </li>
                        <li>Répondre à vos demandes de contact</li>
                        <li>
                            Vous envoyer notre newsletter, si vous y êtes abonné(e), avec possibilité de
                            désinscription à tout moment
                        </li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">4. Base légale du traitement</h2>
                    <ul>
                        <li>
                            <strong>Intérêt légitime</strong> : fonctionnement du site, sécurité, mesure
                            d'audience agrégée
                        </li>
                        <li>
                            <strong>Consentement</strong> : cookies non essentiels, publicités personnalisées,
                            newsletter
                        </li>
                        <li>
                            <strong>Exécution d'une demande</strong> : traitement de vos messages via le
                            formulaire de contact
                        </li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">5. Cookies et gestion du consentement</h2>
                    <p>Camer.be utilise des cookies et technologies similaires pour :</p>
                    <ul>
                        <li>Assurer le bon fonctionnement du site (cookies techniques, toujours actifs)</li>
                        <li>Mesurer l'audience du site (cookies statistiques)</li>
                        <li>Afficher des publicités adaptées, via Google AdSense (cookies publicitaires)</li>
                    </ul>
                    <p>
                        Lors de votre première visite depuis l'Espace économique européen, le Royaume-Uni ou
                        la Suisse, un bandeau de consentement (fourni par Google, conforme au cadre de
                        transparence et de consentement — TCF de l'IAB Europe) vous permet d'accepter, de
                        refuser ou de personnaliser les catégories de cookies utilisées.
                    </p>
                    <p>
                        Vous pouvez à tout moment modifier votre choix ou retirer votre consentement via le
                        lien
                        <a href="#" onclick="window.googlefc && window.googlefc.callbackQueue.push(window.googlefc.showRevocationMessage); return false;">
                            « Gérer mes préférences de confidentialité »
                        </a>
                        disponible en bas de chaque page.
                    </p>
                    <p>
                        Pour en savoir plus sur la manière dont Google traite les données à des fins
                        publicitaires, consultez la
                        <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">politique de confidentialité de Google</a>
                        et la page
                        <a href="https://policies.google.com/technologies/partner-sites" target="_blank" rel="noopener">Comment Google utilise les données</a>.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">6. Partage des données</h2>
                    <p>Nous ne vendons jamais vos données personnelles. Elles peuvent être partagées avec :</p>
                    <ul>
                        <li>
                            <strong>Google (AdSense, Funding Choices, Analytics)</strong> : pour la diffusion
                            publicitaire et la mesure d'audience, dans les limites de votre consentement
                        </li>
                        <li><strong>Notre hébergeur</strong> : pour l'hébergement technique du site</li>
                        <li>Les autorités compétentes, si la loi nous y oblige</li>
                    </ul>
                    <p>Aucune donnée n'est transférée à des fins autres que celles décrites dans cette politique.</p>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">7. Durée de conservation</h2>
                    <ul>
                        <li>Données de navigation / logs techniques : 13 mois maximum</li>
                        <li>Données de contact : le temps nécessaire au traitement de votre demande, puis archivage limité</li>
                        <li>Cookies : selon leur finalité, généralement 13 mois maximum</li>
                        <li>Abonnement newsletter : jusqu'à votre désinscription</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">8. Vos droits</h2>
                    <p>
                        Conformément au Règlement général sur la protection des données (RGPD), vous disposez
                        des droits suivants sur vos données personnelles :
                    </p>
                    <ul>
                        <li>Droit d'accès</li>
                        <li>Droit de rectification</li>
                        <li>Droit à l'effacement</li>
                        <li>Droit à la limitation du traitement</li>
                        <li>Droit à la portabilité des données</li>
                        <li>Droit d'opposition</li>
                        <li>
                            Droit de retirer votre consentement à tout moment, sans affecter la licéité du
                            traitement effectué avant ce retrait
                        </li>
                    </ul>
                    <p>
                        Pour exercer ces droits, contactez-nous à
                        <a href="mailto:{{ $emailContact }}">{{ $emailContact }}</a>.
                        Vous pouvez également introduire une réclamation auprès de l'Autorité de protection
                        des données (APD) belge :
                        <a href="https://www.autoriteprotectiondonnees.be" target="_blank" rel="noopener">www.autoriteprotectiondonnees.be</a>.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">9. Sécurité</h2>
                    <p>
                        Nous mettons en œuvre des mesures techniques et organisationnelles raisonnables pour
                        protéger vos données contre tout accès non autorisé, perte ou altération.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">10. Modifications de cette politique</h2>
                    <p>
                        Cette politique de confidentialité peut être mise à jour périodiquement. La date de
                        dernière mise à jour est indiquée en haut de cette page. Nous vous invitons à la
                        consulter régulièrement.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl sm:text-4xl md:text-3xl font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">11. Contact</h2>
                    <p>
                        Pour toute question relative à cette politique ou à vos données personnelles :<br>
                        <a href="mailto:{{ $emailContact }}">{{ $emailContact }}</a><br>
                        {{ $editeurAdresse }}
                    </p>
                </section>

            </div>
            <aside class="lg:col-span-4 space-y-6">
                {{-- 4. Sous-composant Livewire pour la Sidebar--}}

                <livewire:video :camer="null" :sopie="$sopie" />
                @include('partials.pub-aside')
                <livewire:debat :debat="$debat"/>
                <livewire:droit :droit="$droit"/>
                @include('partials.pub-aside')
                <livewire:video :camer="$camer" :sopie="null" />
                <livewire:skypper :skypper="$skypper" />


            </aside>
        </div>
    </div>
</div>
