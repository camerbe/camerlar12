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
    public $droit;
    public $hash=[];
    public $formatted;
    public $nowFormatted;
    public $camer;
    public $sopie;



    public function mount(
        $oneArticle,
        $plusLus,
        $sameRubrique,
        $debat,
        $droit,
        $camer,
        $sopie=null,
    ){

        $this->article=$oneArticle;
        $this->plusluParPays=$plusLus;
        $this->same=$sameRubrique;
        $this->debat=$debat;
        $this->droit=$droit;
        $arrKeyword=explode(',',$oneArticle['keyword']);
        $this->hash=App\Helpers\Helper::nettoyerHashtags($arrKeyword);
        $this->camer=$camer;
        $this->sopie=$sopie;
        /************************************************************/

        $this->publidhedDate=$this->article['dateparution'];
        $this->dateparutionToDisplay=Carbon::parse($this->publidhedDate)->locale('fr');
        $this->dateparutionToDisplay=ucfirst(
            $this->dateparutionToDisplay->isoFormat('dddd D MMMM YYYY HH:mm')
        );
        $this->dateHeure=Carbon::parse($this->publidhedDate)->format('H:i');

        $titre=$this->article['titre'];
        $articleID=$this->article['id'];

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
        $formatted = Carbon::parse($this->article['dateparution'])->toIso8601String();
        $nowFormatted = Carbon::parse(now())->toIso8601String();


    }
    //
};
?>

<div xmlns:livewire="http://www.w3.org/1999/html">
<!-- ========================================== -->
<!-- FIL D'ARIANE                                -->
<!-- ========================================== -->
    <!-- ========================================== -->
    <!-- FLASH INFO (identique à la home)           -->
    <!-- ========================================== -->

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 mb-4">
        <flux:breadcrumbs class="flex items-center flex-wrap gap-1 text-xs text-gray-500 dark:text-gray-400">
            <flux:breadcrumbs.item href="#" separator="slash">Accueil</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#" separator="slash">{{$this->rubrique}}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="/{{$this->lienCategorie}}" separator="slash"><b>{{$this->sousrubrique}}</b></flux:breadcrumbs.item>

        </flux:breadcrumbs>
    </div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- COLONNE ARTICLE (8 colonnes) -->
    <article class="lg:col-span-8" itemscope itemtype="https://schema.org/NewsArticle">
        <meta itemprop="mainEntityOfPage" [content]="{{url()->current()}}" />
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
            <!-- ==========================================
     LECTEUR AUDIO DE L'ARTICLE
========================================== -->
            <div
                id="articleReader"
                class="mt-5 p-4 rounded-xl
           bg-gray-50 dark:bg-dark-surface
           border border-gray-200 dark:border-dark-border"
            >

                <div class="flex flex-wrap items-center gap-3">

                    <!-- Lecture / pause -->
                    <button
                        type="button"
                        id="readerPlay"
                        class="flex items-center justify-center gap-2
                   px-4 py-2.5 rounded-full
                   bg-brand-500 text-white
                   hover:bg-brand-600
                   transition font-semibold text-sm"
                    >
                        <svg id="readerPlayIcon"
                             class="w-5 h-5"
                             fill="currentColor"
                             viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>

                        <span id="readerPlayText">Écouter l'article</span>
                    </button>

                    <!-- Pause -->
                    <button
                        type="button"
                        id="readerPause"
                        class="hidden items-center justify-center
                   w-10 h-10 rounded-full
                   bg-gray-200 dark:bg-gray-700
                   hover:bg-gray-300 dark:hover:bg-gray-600
                   transition"
                        aria-label="Pause"
                    >
                        <svg class="w-5 h-5"
                             fill="currentColor"
                             viewBox="0 0 24 24">
                            <path d="M7 5h3v14H7zM14 5h3v14h-3z"/>
                        </svg>
                    </button>

                    <!-- Arrêt -->
                    <button
                        type="button"
                        id="readerStop"
                        class="hidden items-center justify-center
                   w-10 h-10 rounded-full
                   bg-gray-200 dark:bg-gray-700
                   hover:bg-gray-300 dark:hover:bg-gray-600
                   transition"
                        aria-label="Arrêter"
                    >
                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M6 6h12v12H6z"/>
                        </svg>
                    </button>

                    <!-- Vitesse -->
                    <select
                        id="readerRate"
                        class="px-3 py-2 rounded-lg
                   bg-white dark:bg-gray-800
                   border border-gray-300 dark:border-gray-600
                   text-sm text-gray-700 dark:text-gray-200
                   focus:ring-2 focus:ring-brand-500"
                    >
                        <option value="0.75">0.75×</option>
                        <option value="1" selected>1×</option>
                        <option value="1.25">1.25×</option>
                        <option value="1.5">1.5×</option>
                        <option value="1.75">1.75×</option>
                    </select>

                    <!-- Voix -->
                    <select
                        id="readerVoice"
                        class="flex-1 min-w-[180px]
                   px-3 py-2 rounded-lg
                   bg-white dark:bg-gray-800
                   border border-gray-300 dark:border-gray-600
                   text-sm text-gray-700 dark:text-gray-200
                   focus:ring-2 focus:ring-brand-500"
                    >
                        <option value="">Voix française</option>
                    </select>

                </div>

                <!-- Statut -->
                <div class="flex items-center justify-between mt-3">

        <span
            id="readerStatus"
            class="text-xs text-gray-500 dark:text-gray-400"
        >
            Écoutez cet article
        </span>

                    <span
                        id="readerProgress"
                        class="text-xs font-semibold text-brand-500"
                    >
            0%
        </span>

                </div>

                <!-- Barre de progression -->
                <div class="mt-2 h-1.5 w-full
                bg-gray-200 dark:bg-gray-700
                rounded-full overflow-hidden">

                    <div
                        id="readerProgressBar"
                        class="h-full bg-brand-500 rounded-full transition-all duration-200"
                        style="width: 0%"
                    ></div>

                </div>

            </div>


            <div class="flex items-center justify-between flex-wrap gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-dark-border">
                <div class="flex items-center space-x-3">


                    <flux:avatar name="{{$auteur}}" color="auto" color:seed="{{ $this->article['id'] }}" class="w-11 h-11"/>
                    <div>
                        <a href="/auteur/{{$auteur}}" class="group inline-block">
                            <div itemprop="author" itemscope itemtype="https://schema.org/Person"
                                 class="relative text-sm font-bold text-gray-900 dark:text-white
                                    transition-all duration-200
                                    group-hover:text-red-600 dark:group-hover:text-red-400
                                    group-hover:-translate-y-0.5" rel="author"

                            >
                                <meta itemprop="name" content="{{$auteur}}">
                                <meta itemprop="url" content="{{ url('/auteur/'.$auteur) }}">
                                <span itemprop="name">{{$auteur}}</span>
                            </div>
                        </a>
                        <meta itemprop="name" content="{{$auteur}}" />

                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <time itemprop="datePublished"  datetime="{{$this->formatted}}">{{$this->dateparutionToDisplay}}</time>
                            <meta itemprop="dateModified" content="{{$this->nowFormatted}}" />
                        </div>
                    </div>
                </div>

                <!-- Barre de partage -->
                @php
                    $shareUrl = url()->current();
                    $title=\App\Helpers\Helper::getTitle($this->article["countries"]["pays"],$this->article['titre'],"")." | Camer.be";
                    $arrKeyword=explode(',',$this->article['keyword']);
                    $hashtags=App\Helpers\Helper::nettoyerHashtags($arrKeyword);
                    $fruits_hashtags = array_map(fn( $hashtag) => '#' .  $hashtag,  $hashtags);
                    $shareText = trim($title . ' ' . implode(', ', $fruits_hashtags) );
                    $encodedUrl = urlencode($shareUrl);
                    $encodedTitle = urlencode($title);
                    $encodedText = urlencode($shareText);
                @endphp
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-400 hidden sm:inline mr-1">Partager</span>
                    <a
                        href="https://www.facebook.com/sharer/sharer.php?u={{$encodedUrl}}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Partager sur Facebook"
                        title="Partager sur Facebook"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-brand-500 hover:text-white transition text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0022 12z"/></svg>
                    </a>
                    <a
                        href="https://twitter.com/intent/tweet?text={{ $encodedText }}&url={{ $encodedUrl }}"
                        aria-label="Partager sur X"
                        title="Partager sur X"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-brand-500 hover:text-white transition text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.24 2H21l-6.5 7.4L22 22h-6.4l-5-6.5L4.7 22H2l7-8-7.7-12h6.6l4.5 6z"/></svg>
                    </a>
                    <a
                        href="https://wa.me/?text={{ urlencode($shareText . "\n\n" . $shareUrl) }}"
                        aria-label="Partager sur WhatsApp"
                        title="Partager sur WhatsApp"
                        rel="noopener noreferrer"
                        target="_blank"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-accent-500 hover:text-white transition text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 00-8.5 15.2L2 22l4.9-1.5A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-3 .9.9-2.9-.2-.3A8 8 0 1112 20z"/></svg>
                    </a>
                    {{-- Telegram --}}
                    <a
                        href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedText }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Partager sur Telegram"
                        title="Partager sur Telegram"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-[#229ED9] hover:text-white transition-all duration-200 hover:scale-110" > <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"> <path d="M21.5 3.5L18.2 20c-.2 1.1-.9 1.4-1.8.9l-5-3.7-2.4 2.3c-.3.3-.5.5-1 .5l.4-5.1 9.3-8.4c.4-.4-.1-.6-.6-.2L5.6 13.7.7 12.2c-1.1-.3-1.1-1.1.2-1.6L20 3.1c.9-.3 1.7.2 1.5.4z"/> </svg>
                    </a>
                    {{-- LinkedIn --}}
                    <a
                        href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Partager sur LinkedIn"
                        title="Partager sur LinkedIn"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-[#0A66C2] hover:text-white transition-all duration-200 hover:scale-110" > <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"> <path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.34V8.98h3.42v1.56h.05c.48-.9 1.64-1.85 3.38-1.85 3.61 0 4.28 2.38 4.28 5.48v6.28zM5.32 7.43a2.07 2.07 0 110-4.14 2.07 2.07 0 010 4.14zM3.54 8.98h3.57v11.47H3.54V8.98z"/> </svg>
                    </a>
                    <button aria-label="Copier le lien" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-900 hover:text-white transition text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5M10.172 13.828a4 4 0 010-5.656l3-3a4 4 0 015.656 5.656l-1.5 1.5"/></svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Image à la une -->
        <figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject" class="mb-8 rounded-2xl overflow-hidden shadow-md">

            <img itemprop="thumbnailUrl" src="{{$this->img}}" width="1200" height="675" alt="{{$this->titre}}" fetchpriority="high" loading="eager"  class="w-full h-auto object-cover">

            <meta itemprop="url" content="{{$this->img}}" />
            <meta itemprop="width" content="{{$this->article["image_width"]}}" />
            <meta itemprop="height" content="{{$this->article["image_height"]}}" />
            <meta itemprop="articleSection" content="{{$this->sousrubrique}}" />
            <figcaption class="text-xs text-gray-500 dark:text-gray-400 ">
                <div class="flex flex-col items-center justify-center">
                    <span class="text-[10px] uppercase text-gray-400 font-semibold tracking-wider mb-1">Publicité</span>
                    <div class="h-[90px] w-full max-w-[728px] bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-xs text-gray-500 rounded overflow-hidden">
                        <span>Espace Publicitaire (AdSense Banner 728x90)</span>
                    </div>
                </div>
            </figcaption>
        </figure>

        <!-- Corps de l'article -->
        <meta itemprop="description" content="{{$this->article['chapeau']}}" />
        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
            <meta itemprop="name" content="Camer.be">
        </div>


        <div  itemprop="articleBody"
            class="article-body
            [&_table]:w-full
            [&_table]:my-6
            [&_table]:border-collapse
            [&_table]:text-sm
            [&_table]:overflow-hidden
            [&_thead]:bg-gray-100
            [&_thead]:dark:bg-gray-800
            [&_th]:px-4
            [&_th]:py-3
            [&_th]:text-left
            [&_th]:font-bold
            [&_th]:border
            [&_th]:border-gray-300
            [&_th]:dark:border-gray-700
            [&_td]:px-4
            [&_td]:py-3
            [&_td]:border
            [&_td]:border-gray-300
            [&_td]:dark:border-gray-700
            [&_td]:align-top
            [&_tr:nth-child(even)]:bg-gray-50
            [&_tr:nth-child(even)]:dark:bg-gray-900/50


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
            <div class="flex flex-wrap items-center gap-2 mb-8">
                @if(count($this->hash)>0)
                    @foreach($this->hash as $hash)
                        <a href="#" class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-brand-500 hover:text-white transition">#{{$hash}}</a>
                    @endforeach
                @endif


            </div>
        </div>
        <!-- Abonnement chaîne WhatsApp -->
        <div class="my-4">
            <a
                href="https://chat.whatsapp.com/CtYk9hlYGigJN4k0RDWfYG"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="S'abonner à notre chaîne WhatsApp"
                class="group flex items-center gap-4 p-4 sm:p-5
               rounded-2xl
               bg-[#25D366]/10
               dark:bg-[#25D366]/10
               border border-[#25D366]/20
               hover:border-[#25D366]/50
               hover:bg-[#25D366]/15
               transition-all duration-300
               no-underline"
            >

                <!-- Icône WhatsApp -->
                <span
                    class="flex-shrink-0 flex items-center justify-center
                   w-12 h-12 sm:w-14 sm:h-14
                   rounded-full
                   bg-[#25D366]
                   text-white
                   shadow-md
                   group-hover:scale-105
                   transition-transform duration-300"
                >
            <svg
                class="w-7 h-7 sm:w-8 sm:h-8"
                viewBox="0 0 24 24"
                fill="currentColor"
                aria-hidden="true"
            >
                <path d="M20.52 3.48A11.82 11.82 0 0 0 12.08 0
                    C5.55 0 .24 5.31.24 11.84c0 2.09.55 4.13
                    1.59 5.93L.13 24l6.39-1.67a11.84 11.84 0 0 0
                    5.56 1.39h.01c6.53 0 11.84-5.31 11.84-11.84
                    0-3.17-1.24-6.14-3.41-8.4ZM12.09 21.7h-.01
                    a9.84 9.84 0 0 1-5.02-1.37l-.36-.21-3.79.99
                    1.01-3.69-.23-.38a9.82 9.82 0 0 1-1.5-5.2
                    c0-5.42 4.41-9.83 9.84-9.83 2.63 0 5.1 1.03
                    6.96 2.88a9.77 9.77 0 0 1 2.88 6.96
                    c0 5.43-4.42 9.85-9.78 9.85Zm5.4-7.37
                    c-.3-.15-1.77-.87-2.05-.97-.28-.1-.48-.15-.68.15
                    -.2.3-.78.97-.96 1.17-.18.2-.35.22-.65.07
                    -.3-.15-1.27-.47-2.42-1.5-.9-.8-1.5-1.78-1.68-2.08
                    -.18-.3-.02-.46.13-.61.13-.13.3-.35.45-.52
                    .15-.18.2-.3.3-.5.1-.2.05-.37-.03-.52
                    -.08-.15-.68-1.64-.93-2.25-.25-.59-.5-.51-.68-.52
                    -.18-.01-.38-.01-.58-.01-.2 0-.52.07-.8.37
                    -.28.3-1.05 1.03-1.05 2.52s1.08 2.92 1.23 3.12
                    c.15.2 2.12 3.24 5.14 4.54.72.31 1.28.5 1.72.64
                    .72.23 1.38.2 1.9.12.58-.09 1.77-.72 2.02-1.42
                    .25-.7.25-1.3.17-1.42-.08-.12-.28-.2-.58-.35Z"
                />
            </svg>
        </span>

                <!-- Texte -->
                <span class="flex-1 min-w-0">
            <span class="block text-xs sm:text-sm font-medium
                         text-gray-500 dark:text-gray-400 mb-0.5">
                Restez informé de notre actualité
            </span>

            <span class="block text-base sm:text-lg font-extrabold
                         text-gray-900 dark:text-white
                         group-hover:text-[#128C7E]
                         transition-colors">
                Rejoignez notre chaîne WhatsApp
            </span>

            <span class="block mt-1 text-xs sm:text-sm
                         text-gray-600 dark:text-gray-300">
                Recevez nos dernières informations directement sur WhatsApp.
            </span>
        </span>

                <!-- Flèche -->
                <span
                    class="flex-shrink-0 flex items-center justify-center
                   w-9 h-9 rounded-full
                   bg-white dark:bg-gray-800
                   text-[#25D366]
                   shadow-sm
                   group-hover:translate-x-1
                   transition-transform duration-300"
                >
            <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                />
            </svg>
        </span>

            </a>
        </div>
        <div class="flex items-start gap-4  rounded-xl  dark:bg-dark-surface border border-gray-100 dark:border-dark-border mb-10">
            <flux:avatar name="{{$auteur}}" color="auto" color:seed="{{ $this->article['id'] }}"  class="w-11 h-11"/>
            <div>
                <div class="text-sm font-bold text-gray-900 dark:text-white">{{$auteur}}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                    <a
                        href="/auteur/{{$auteur}}"
                        class="hover:text-brand-500 dark:hover:text-brand-400 hover:underline transition-colors duration-200"
                    >
                        Retrouvez ses articles
                    </a>

                </div>

            </div>
        </div>

        <!-- Publicité native intégrée au contenu -->
        <div class="flex flex-col items-center justify-center my-8">

            <span class="text-[10px] uppercase text-gray-400 font-semibold tracking-wider mb-1">Publicité</span>
            <div class="h-[90px] w-full max-w-[728px] bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-xs text-gray-500 rounded overflow-hidden">
                <span>Espace Publicitaire (AdSense Banner 728x90)</span>

            </div>
        </div>


        <!-- Bloc auteur -->



        </section>

        <!-- Articles liés -->
        <section>
            <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2 mb-6">
                <h2 class="text-lg font-extrabold font-heading uppercase tracking-wide">
                    À lire aussi
                </h2>
            </div>
            <livewire:same-rubrique   :sameRubrique="$sameRubrique" />
            <div  class="mt-5 h-[900px] overflow-auto" >
                <div id="taboola-below-article-thumbnails"></div>
                <script>
                    window._taboola = window._taboola || [];

                    _taboola.push({
                        mode: 'thumbnails-a',
                        container: 'taboola-below-article-thumbnails',
                        placement: 'Below Article Thumbnails',
                        target_type: 'mix'
                    });

                    _taboola.push({flush: true});
                </script>
            </div>


        </section>

    </article>

    <!-- SIDEBAR (4 colonnes, identique à la home) -->
    <aside class="lg:col-span-4 space-y-8">
        <livewire:most-readed-rubrique-country
            :plusluParPays="$plusLus"
       />
        <livewire:video :camer="null" :sopie="$sopie" />
        @include('partials.pub-aside')
        <livewire:debat :debat="$debat"/>
        <livewire:droit :droit="$droit"/>
        @include('partials.pub-aside')
        <livewire:video :camer="$camer" :sopie="null" />
        <livewire:skypper :skypper="$skypper"  />
    </aside>

</div>
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Vérification du navigateur
            |--------------------------------------------------------------------------
            */

            if (!('speechSynthesis' in window)) {

                const reader = document.getElementById('articleReader');

                if (reader) {
                    reader.style.display = 'none';
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Éléments
            |--------------------------------------------------------------------------
            */

            const playButton       = document.getElementById('readerPlay');
            const playText         = document.getElementById('readerPlayText');
            const playIcon         = document.getElementById('readerPlayIcon');

            const pauseButton      = document.getElementById('readerPause');
            const stopButton       = document.getElementById('readerStop');

            const rateSelect       = document.getElementById('readerRate');
            const voiceSelect      = document.getElementById('readerVoice');

            const status            = document.getElementById('readerStatus');
            const progressText      = document.getElementById('readerProgress');
            const progressBar       = document.getElementById('readerProgressBar');

            const articleBody       = document.querySelector('[itemprop="articleBody"]');


            if (!articleBody) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Récupération du contenu
            |--------------------------------------------------------------------------
            */

            function getArticleText() {

                const clone = articleBody.cloneNode(true);

                /*
                | On supprime les éléments qui ne doivent pas être lus.
                */

                clone.querySelectorAll(
                    'script, style, iframe, video, audio, img'
                ).forEach(element => element.remove());

                return clone.innerText
                    .replace(/\s+/g, ' ')
                    .trim();
            }


            /*
            |--------------------------------------------------------------------------
            | Variables
            |--------------------------------------------------------------------------
            */

            let articleText = getArticleText();

            let utterance = null;

            let isReading = false;

            let isPaused = false;

            let currentPosition = 0;

            let chunks = [];


            /*
            |--------------------------------------------------------------------------
            | Découpage du texte
            |--------------------------------------------------------------------------
            |
            | SpeechSynthesis peut avoir des problèmes avec des textes très longs.
            | On découpe donc l'article en morceaux.
            |
            */

            function splitText(text, maxLength = 220) {

                const sentences = text.match(
                    /[^.!?]+[.!?]+|[^.!?]+$/g
                ) || [];

                const result = [];

                let current = '';

                sentences.forEach(sentence => {

                    sentence = sentence.trim();

                    if (
                        current.length + sentence.length + 1
                        > maxLength
                    ) {

                        if (current) {
                            result.push(current);
                        }

                        current = sentence;

                    } else {

                        current += ' ' + sentence;
                    }
                });

                if (current) {
                    result.push(current);
                }

                return result;
            }


            chunks = splitText(articleText);


            /*
            |--------------------------------------------------------------------------
            | Chargement des voix
            |--------------------------------------------------------------------------
            */

            function loadVoices() {

                const voices = speechSynthesis.getVoices();

                voiceSelect.innerHTML =
                    '<option value="">Voix française</option>';

                const frenchVoices = voices.filter(voice =>
                    voice.lang &&
                    voice.lang.toLowerCase().startsWith('fr')
                );

                frenchVoices.forEach((voice, index) => {

                    const option = document.createElement('option');

                    option.value = voice.name;

                    option.textContent =
                        `${voice.name} (${voice.lang})`;

                    voiceSelect.appendChild(option);
                });
            }


            loadVoices();

            speechSynthesis.onvoiceschanged = loadVoices;


            /*
            |--------------------------------------------------------------------------
            | Récupérer la voix
            |--------------------------------------------------------------------------
            */

            function getSelectedVoice() {

                const voices = speechSynthesis.getVoices();

                return voices.find(
                    voice => voice.name === voiceSelect.value
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Mise à jour de l'interface
            |--------------------------------------------------------------------------
            */

            function updateProgress() {

                if (!chunks.length) {
                    return;
                }

                const percent = Math.round(
                    (currentPosition / chunks.length) * 100
                );

                progressText.textContent = `${percent}%`;

                progressBar.style.width = `${percent}%`;
            }


            /*
            |--------------------------------------------------------------------------
            | Lecture d'un morceau
            |--------------------------------------------------------------------------
            */

            function speakChunk() {

                if (currentPosition >= chunks.length) {

                    finishReading();

                    return;
                }

                utterance = new SpeechSynthesisUtterance(
                    chunks[currentPosition]
                );

                utterance.lang = 'fr-FR';

                utterance.rate =
                    parseFloat(rateSelect.value);

                utterance.pitch = 1;

                utterance.volume = 1;


                const voice = getSelectedVoice();

                if (voice) {
                    utterance.voice = voice;
                    utterance.lang = voice.lang;
                }


                /*
                | Début
                */

                utterance.onstart = function () {

                    isReading = true;
                    isPaused = false;

                    updateInterface();

                    status.textContent =
                        `Lecture de l'article…`;
                };


                /*
                | Fin du morceau
                */

                utterance.onend = function () {

                    if (!isReading) {
                        return;
                    }

                    currentPosition++;

                    updateProgress();

                    speakChunk();
                };


                /*
                | Erreur
                */

                utterance.onerror = function (event) {

                    console.error(
                        'Erreur SpeechSynthesis:',
                        event
                    );

                    finishReading();
                };


                speechSynthesis.speak(utterance);
            }


            /*
            |--------------------------------------------------------------------------
            | Démarrer
            |--------------------------------------------------------------------------
            */

            function startReading() {

                if (!articleText) {
                    return;
                }


                /*
                | Si on était à la fin, on recommence.
                */

                if (currentPosition >= chunks.length) {
                    currentPosition = 0;
                }


                speechSynthesis.cancel();

                isReading = true;

                isPaused = false;

                updateInterface();

                speakChunk();
            }


            /*
            |--------------------------------------------------------------------------
            | Pause
            |--------------------------------------------------------------------------
            */

            function pauseReading() {

                if (!isReading) {
                    return;
                }

                speechSynthesis.pause();

                isPaused = true;

                status.textContent =
                    'Lecture en pause';

                updateInterface();
            }


            /*
            |--------------------------------------------------------------------------
            | Reprendre
            |--------------------------------------------------------------------------
            */

            function resumeReading() {

                if (!isReading) {
                    return;
                }

                speechSynthesis.resume();

                isPaused = false;

                status.textContent =
                    'Lecture en cours…';

                updateInterface();
            }


            /*
            |--------------------------------------------------------------------------
            | Arrêter
            |--------------------------------------------------------------------------
            */

            function stopReading() {

                speechSynthesis.cancel();

                isReading = false;

                isPaused = false;

                currentPosition = 0;

                updateProgress();

                status.textContent =
                    'Écoutez cet article';

                updateInterface();
            }


            /*
            |--------------------------------------------------------------------------
            | Fin
            |--------------------------------------------------------------------------
            */

            function finishReading() {

                speechSynthesis.cancel();

                isReading = false;

                isPaused = false;

                currentPosition = 0;

                progressText.textContent = '100%';

                progressBar.style.width = '100%';

                status.textContent =
                    'Lecture terminée';

                updateInterface();
            }


            /*
            |--------------------------------------------------------------------------
            | Interface
            |--------------------------------------------------------------------------
            */

            function updateInterface() {

                if (isReading) {

                    pauseButton.classList.remove('hidden');
                    pauseButton.classList.add('flex');

                    stopButton.classList.remove('hidden');
                    stopButton.classList.add('flex');

                    if (isPaused) {

                        playText.textContent =
                            'Reprendre';

                        playIcon.innerHTML =
                            '<path d="M8 5v14l11-7z"/>';

                    } else {

                        playText.textContent =
                            'Lecture en cours';

                        playIcon.innerHTML =
                            '<path d="M6 4h4v16H6zM14 4h4v16h-4z"/>';
                    }

                } else {

                    pauseButton.classList.add('hidden');
                    pauseButton.classList.remove('flex');

                    stopButton.classList.add('hidden');
                    stopButton.classList.remove('flex');

                    playText.textContent =
                        "Écouter l'article";

                    playIcon.innerHTML =
                        '<path d="M8 5v14l11-7z"/>';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Bouton principal
            |--------------------------------------------------------------------------
            */

            playButton.addEventListener('click', function () {

                if (!isReading) {

                    startReading();

                } else if (isPaused) {

                    resumeReading();

                } else {

                    pauseReading();
                }

            });


            /*
            |--------------------------------------------------------------------------
            | Bouton pause
            |--------------------------------------------------------------------------
            */

            pauseButton.addEventListener(
                'click',
                pauseReading
            );


            /*
            |--------------------------------------------------------------------------
            | Bouton stop
            |--------------------------------------------------------------------------
            */

            stopButton.addEventListener(
                'click',
                stopReading
            );


            /*
            |--------------------------------------------------------------------------
            | Changement de vitesse
            |--------------------------------------------------------------------------
            */

            rateSelect.addEventListener(
                'change',
                function () {

                    if (!isReading) {
                        return;
                    }

                    /*
                    | On relance le morceau actuel avec
                    | la nouvelle vitesse.
                    */

                    speechSynthesis.cancel();

                    speakChunk();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Changement de voix
            |--------------------------------------------------------------------------
            */

            voiceSelect.addEventListener(
                'change',
                function () {

                    if (!isReading) {
                        return;
                    }

                    speechSynthesis.cancel();

                    speakChunk();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Nettoyage
            |--------------------------------------------------------------------------
            */

            window.addEventListener(
                'beforeunload',
                function () {
                    speechSynthesis.cancel();
                }
            );

        });
    </script>
</div>
