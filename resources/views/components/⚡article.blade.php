<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Session;
use App\Services\ArticleService;
use Illuminate\Support\Str;
use App\Helpers\Helper;
use Carbon\Carbon;


new class extends Component
{
    public $article=null;
    public $oneArticle=null;

    public $plusluParPays=null;
    public $plusLus=null;
    public $same=[];
    public $sameRubrique;


    public $rubrique;
    public $sousrubrique;
    public $titre;
    public $auteur;
    public $hit;
    public $date_display;
    public $published;
    public $img;
    public $lienCategorie;
    public $dateparutionToDisplay;
    public $publidhedDate;
    public $mimeType;
    public $dateHeure;
    public $debat;


    public function mount(){

        $this->article=$this->oneArticle;
        $this->plusluParPays=$this->plusLus;
        $this->same=$this->sameRubrique;
        //dd($this->article);
        /************************************************************/

        $this->publidhedDate=$this->article['dateparution'];
        $this->dateparutionToDisplay=Carbon::parse($this->publidhedDate)->locale('fr');
        $this->dateparutionToDisplay=ucfirst(
            $this->dateparutionToDisplay->isoFormat('dddd D MMMM YYYY HH:mm')
        );
        $this->dateHeure=Carbon::parse($this->publidhedDate)->format('H:i');

        $titre=$this->article['titre'];
        $chapo=$this->article['chapeau'];
        $this->rubrique=Str::title($this->article['rubrique']['rubrique']) ;
        //$this->img=Helper::extractImgSrc($this->article->image) ?? 'https://picsum.photos/1200/675?random=1';
        $this->img=$this->article['image_url'] ?? 'https://picsum.photos/1200/675?random=1';
        //$this->mimeType=Helper::getImageMimeType($this->img);
        $this->mimeType=$this->article['image_mimetype'] ?? 'image/webp';
        $this->auteur=$this->article['auteur'];
        $this->hit=$this->article['hit'];
        $this->sousrubrique=Str::title($this->article['sousrubrique']['sousrubrique']) ;
        $this->lienCategorie=Str::slug($this->rubrique)."/".Str::slug($this->sousrubrique);
        //dd($this->debat);
    }
    //
};
?>

<div>
<!-- ========================================== -->
<!-- FIL D'ARIANE                                -->
<!-- ========================================== -->
    @php

    @endphp
        <!-- ========================================== -->
    <!-- FLASH INFO (identique à la home)           -->
    <!-- ========================================== -->

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 mb-4">
        <flux:breadcrumbs class="flex items-center flex-wrap gap-1 text-xs text-gray-500 dark:text-gray-400">
            <flux:breadcrumbs.item href="#" separator="slash">Accueil</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#" separator="slash">{{$this->rubrique}}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="/{{$this->lienCategorie}}" separator="slash">{{$this->sousrubrique}}</flux:breadcrumbs.item>

        </flux:breadcrumbs>
    </div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- COLONNE ARTICLE (8 colonnes) -->
    <article class="lg:col-span-8">

        <!-- En-tête article -->
        <header class="mb-6">
            <div class="flex items-center space-x-2 mb-4">
                        <span class="bg-accent-500 text-white font-bold text-xs px-3 py-1 uppercase rounded-full shadow">
                            {{$this->sousrubrique}}
                        </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-500">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500 live-dot"></span>
                            {{$this->hit}} personnes lisent cet article
                        </span>
            </div>

            <h1 itemprop="headline" class="uppercase text-3xl sm:text-4xl md:text-[2.75rem] font-extrabold font-heading leading-[1.1] text-gray-900 dark:text-white">
                {{$article['titre']}}
            </h1>



            <div class="flex items-center justify-between flex-wrap gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-dark-border">
                <div class="flex items-center space-x-3">
                    <img src="https://picsum.photos/80/80?random=11" alt="Armand K." class="w-11 h-11 rounded-full object-cover">
                    <div>
                        <div class="text-sm font-bold text-gray-900 dark:text-white" rel="author">{{$auteur}}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <time datetime="{{$this->publidhedDate}}">{{$this->dateparutionToDisplay}}</time>
                        </div>
                    </div>
                </div>

                <!-- Barre de partage -->
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-400 hidden sm:inline mr-1">Partager</span>
                    <a href="#" aria-label="Partager sur Facebook" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-brand-500 hover:text-white transition text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0022 12z"/></svg>
                    </a>
                    <a href="#" aria-label="Partager sur X" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-brand-500 hover:text-white transition text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.24 2H21l-6.5 7.4L22 22h-6.4l-5-6.5L4.7 22H2l7-8-7.7-12h6.6l4.5 6z"/></svg>
                    </a>
                    <a href="#" aria-label="Partager sur WhatsApp" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-accent-500 hover:text-white transition text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 00-8.5 15.2L2 22l4.9-1.5A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-3 .9.9-2.9-.2-.3A8 8 0 1112 20z"/></svg>
                    </a>
                    <button aria-label="Copier le lien" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-900 hover:text-white transition text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5M10.172 13.828a4 4 0 010-5.656l3-3a4 4 0 015.656 5.656l-1.5 1.5"/></svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Image à la une -->
        <figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject" class="mb-8 rounded-2xl overflow-hidden shadow-md">
            <img src="{{$this->img}}" width="1200" height="675" alt="{{$this->titre}}" fetchpriority="high" loading="eager" itemprop="url" class="w-full h-auto object-cover">
        </figure>
        <!-- Corps de l'article -->
        <div
            class="article-body
            [&_img]:w-full
            [&_img]:h-auto
            [&_img]:rounded-lg
            [&_img]:my-4
            [&_img]:object-cover
            [&_video]:w-full
            [&_video]:h-auto
            [&_video]:my-4
            [&_video]:rounded-lg
            [&_iframe]:w-full
            [&_iframe]:aspect-video
            [&_iframe]:my-4
            [&_iframe]:rounded-lg
            [&_iframe]:border-0
            [&_p]:mb-4 [&_p:last-child]:mb-0
            md:[&_h2]:text-2xl [&_h2]:uppercase  [&_h2]:mb-2 [&_h2]:text-gray-950 [&_h2]:dark:text-white [&_h2]:font-bold
            leading-relaxed font-read text-[1.05rem] text-gray-800 dark:text-gray-200 text-justify">
            {!! $article['info'] !!}
        </div>

        <!-- Publicité native intégrée au contenu -->
        <div class="flex flex-col items-center justify-center my-8">
            <span class="text-[10px] uppercase text-gray-400 font-semibold tracking-wider mb-1">Publicité</span>
            <div class="h-[90px] w-full max-w-[728px] bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-xs text-gray-500 rounded overflow-hidden">
                <span>Espace Publicitaire (AdSense Banner 728x90)</span>
            </div>
        </div>

        <!-- Tags -->
        <div class="flex flex-wrap items-center gap-2 mb-8">
            <a href="#" class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-brand-500 hover:text-white transition">#CEMAC</a>
            <a href="#" class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-brand-500 hover:text-white transition">#Cameroun</a>
            <a href="#" class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-brand-500 hover:text-white transition">#Économie</a>
            <a href="#" class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-brand-500 hover:text-white transition">#Intégration régionale</a>
        </div>

        <!-- Réactions (composant type Livewire) -->
        <div class="flex items-center justify-between flex-wrap gap-4 p-4 rounded-xl bg-white dark:bg-dark-surface border border-gray-100 dark:border-dark-border mb-10">
            <div class="flex items-center gap-1">
                <button class="flex items-center gap-1.5 px-3 py-2 rounded-full text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-highlight-500/20 hover:text-highlight-600 transition">
                    👍 <span>312</span>
                </button>
                <button class="flex items-center gap-1.5 px-3 py-2 rounded-full text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-highlight-500/20 hover:text-highlight-600 transition">
                    😮 <span>48</span>
                </button>
                <button class="flex items-center gap-1.5 px-3 py-2 rounded-full text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-highlight-500/20 hover:text-highlight-600 transition">
                    😡 <span>15</span>
                </button>
            </div>
            <div class="text-xs text-gray-400">Mis à jour en direct</div>
        </div>

        <!-- Bloc auteur -->
        <div class="flex items-start gap-4 p-5 rounded-xl bg-white dark:bg-dark-surface border border-gray-100 dark:border-dark-border mb-10">
            <img src="https://picsum.photos/80/80?random=11" alt="Armand K." class="w-14 h-14 rounded-full object-cover shrink-0">
            <div>
                <div class="text-sm font-bold text-gray-900 dark:text-white">Armand K.</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Journaliste économie, Camer.be</div>
                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                    Suit l'actualité économique de la sous-région CEMAC et les dossiers d'intégration régionale depuis Yaoundé.
                </p>
            </div>
        </div>

        <!-- Commentaires (composant Livewire) -->
        <section class="mb-10">
            <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2 mb-6">
                <h2 class="text-lg font-extrabold font-heading uppercase tracking-wide">
                    Commentaires
                </h2>
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">27 réactions</span>
            </div>

            <form class="flex items-start gap-3 mb-8">
                <img src="https://picsum.photos/60/60?random=20" alt="Votre avatar" class="w-9 h-9 rounded-full object-cover shrink-0">
                <div class="flex-1">
                    <textarea rows="3" placeholder="Partagez votre avis sur cet article..." class="w-full text-sm px-3 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                    <div class="flex justify-end mt-2">
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 rounded-md transition">Publier</button>
                    </div>
                </div>
            </form>

            <div class="space-y-5">
                <div class="flex items-start gap-3">
                    <img src="https://picsum.photos/60/60?random=21" alt="Commentateur" class="w-9 h-9 rounded-full object-cover shrink-0">
                    <div class="flex-1 bg-gray-50 dark:bg-gray-800/60 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">Nadège E.</span>
                            <span class="text-[11px] text-gray-400">Il y a 12 min</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Espérons que cette fois les engagements seront suivis d'effets concrets sur le terrain.</p>
                        <button class="text-xs font-semibold text-brand-500 mt-2">Répondre</button>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <img src="https://picsum.photos/60/60?random=22" alt="Commentateur" class="w-9 h-9 rounded-full object-cover shrink-0">
                    <div class="flex-1 bg-gray-50 dark:bg-gray-800/60 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">Paul-Henri M.</span>
                            <span class="text-[11px] text-gray-400">Il y a 34 min</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">La suppression des visas de court séjour serait vraiment un pas décisif pour la diaspora.</p>
                        <button class="text-xs font-semibold text-brand-500 mt-2">Répondre</button>
                    </div>
                </div>
            </div>

            <button class="mt-6 text-xs font-bold text-brand-500 hover:underline">Charger plus de commentaires</button>
        </section>

        <!-- Articles liés -->
        <section>
            <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2 mb-6">
                <h2 class="text-lg font-extrabold font-heading uppercase tracking-wide">
                    À lire aussi
                </h2>
            </div>
            <livewire:same-rubrique   :sameRubrique="$sameRubrique" />

        </section>

    </article>

    <!-- SIDEBAR (4 colonnes, identique à la home) -->
    <aside class="lg:col-span-4 space-y-8">
        <livewire:most-readed-rubrique-country

            :plusluParPays="$plusLus"
       />


        <!-- Widget sommaire de l'article (ancres) -->
        <div class="bg-white dark:bg-dark-surface p-6 rounded-xl border border-gray-100 dark:border-dark-border shadow-sm">
            <h3 class="text-lg font-bold font-heading mb-4 border-l-4 border-accent-500 pl-3">
                📑 Dans cet article
            </h3>
            <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-300">
                <li><a href="#" class="hover:text-brand-500 transition">Une relance portée par l'agriculture et les infrastructures</a></li>
                <li><a href="#" class="hover:text-brand-500 transition">Libre circulation : des avancées attendues d'ici fin 2026</a></li>
            </ul>
        </div>

        <div class="flex flex-col items-center justify-center">
            <span class="text-[10px] uppercase text-gray-400 font-semibold tracking-wider mb-1">Publicité</span>
            <div class="h-[250px] w-[300px] bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-xs text-gray-500 rounded">
                <span>AdSense (300x250)</span>
            </div>
        </div>

        <!-- Newsletter -->
        <livewire:debat :debat="$debat"/>

    </aside>

</div>
</div>
