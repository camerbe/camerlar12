@php
    $newsArticle=\App\Helpers\Helper::convertImgToAmpImg($article['info']);
    $newsArticle=\App\Helpers\Helper::convertYoutubeToAmp($newsArticle);
    $today = now()->format('Y-m-d\TH:i:s+00:00');
    $articleDate = \Carbon\Carbon::parse($article['dateparution'])->format('Y-m-d\TH:i:s+00:00');
    $year = \Carbon\Carbon::parse($article['dateparution'])->year;
    $articleUrl =url("amp/". Illuminate\Support\Str::slug(strtolower($article['rubrique']['rubrique'])) . '/' .
                      Illuminate\Support\Str::slug(strtolower($article['sousrubrique']['sousrubrique'])) . '/' .
                      $article['slug']);
    $description =$article['chapeau'];
    $jsonLdArticle= [
            "@context" => "https://schema.org",
            "@type" => "NewsArticle",

            "headline" => $article['titre'],
            "description" => $description,
            "articleSection" => $article['rubrique']['rubrique'] . " / " . $article['sousrubrique']['sousrubrique'],
            "keywords" => array_map('trim', explode(',', $article['keyword'])),
            "inLanguage" => "fr-FR",
            "url" => url()->current(),
            "datePublished" => $articleDate,
            "dateModified" => $today,
            "isAccessibleForFree" => "True",
            "copyrightYear" => $year,

            "author" => [
                "@type" => "Person",
                "name" => $article['auteur']
            ],
            "editor" => [
                "@type" => "Person",
                "name" => $article['source']
            ],

            "publisher" => [
                "@type" => "Organization",
                "name" => "Camer.be",
                "url" => "https://www.camer.be",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => url('assets/img/camer-logo.png'),
                    "width" => 190,
                    "height" => 52
                ]
            ],

            "image" => [
                "@type" => "ImageObject",
                "url" => $article['image_url'],
                "width" => $article['image_width'],
                "height" => $article['image_height'],
                "caption" => "{$article['countries']['pays']} :: {$article['titre']} - Camer.be"
            ],

            "contentLocation" => [
                "@type" => "Place",
                "name" => $article['countries']['pays']
            ],

            "articleBody" => $article['info'],

            "interactionStatistic" => [
                [
                    "@type" => "InteractionCounter",
                    "interactionType" => [
                        "@type" => "http://schema.org/ReadAction"
                    ],
                    "userInteractionCount" => $article['hit']
                ]
            ],

            "sameAs" => [
                "https://www.facebook.com/camergroup",
                "https://x.com/camerbe"
            ]
        ];
@endphp
<div class="material-box  full-top" style="text-align: justify;" itemscope itemtype="https://schema.org/NewsArticle">
        <link itemprop="url" href="{{ $articleUrl }}">
        <amp-img
            title="{{$article['titre']}}"
            alt="{{$article['titre']}}"
            class="full-bottom"
            src="{{$article['image_url']}}"
            width="{{$article['image_width']}}"
            height="{{$article['image_height']}}"
            layout="responsive">

        </amp-img>
        <meta itemprop="thumbnailUrl" content="{{$article['image_url']}}" />
        <meta itemprop="description" content="{{$description}}" />
        <meta itemprop="datePublished" content="{{ $articleDate }}">
        <meta itemprop="dateModified" content="{{ $today }}">
        <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
            <meta itemprop="name" content="Camer.be" />
            <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
              <meta itemprop="url" content="{{ url('assets/img/camer-logo.png')}}" />
              <meta itemprop="width" content="190" />
              <meta itemprop="height" content="52" />
            </span>
        </span>
        <div itemprop="author" itemscope itemtype="https://schema.org/Person">
            <meta itemprop="name" content="{{$article['auteur']}}">
            <meta itemprop="url" content="{{url()->current()}}" />
        </div>
        <div itemprop="contentLocation" itemscope itemtype="https://schema.org/Place">
            <meta itemprop="name" content="{{$article['countries']['pays']}}">
        </div>
        <div itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">
            <meta itemprop="interactionType" content="https://schema.org/ReadAction" />
            <meta itemprop="userInteractionCount" content="{{$article['hit']}}" />
        </div>

        <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="{{$article['image_url']}}" />
            <meta itemprop="width" content="{{$article['image_width']}}" />
            <meta itemprop="height" content="{{$article['image_height']}}" />
        </div>
        <meta itemprop="keywords" content="{{$article['keyword']}}" />
        <meta itemprop="articleSection" content="{{$article['rubrique']['rubrique']}} / {{$article['sousrubrique']['sousrubrique']}}" />
        <meta itemprop="inLanguage" content="fr-FR">
        <h4 class="news-post-title uppercase" itemprop="headline" >{{$article['titre']}}</h4>
        <em class="news-post-info">
            <span>&copy; {{$article['source']}} : {{$article['auteur']}}</span>  |
            <span>{{ \Carbon\Carbon::parse($article['dateparution'])->translatedFormat('d M Y H:i:s')}}</span>  |
            <span><i class="fa fa-eye"></i> {{$article['hit']}}</span>
        </em>
        <div itemprop="articleBody">
            {!!$newsArticle!!}
        </div>
         <em>Pour plus d'informations sur l'actualité, abonnez vous sur :
             <a href="https://chat.whatsapp.com/CtYk9hlYGigJN4k0RDWfYG"><strong> notre chaîne WhatsApp </strong></a>
         </em>
        @include('partials.amp-samerubrique')
        <div class="decoration"></div>
        @include('partials.amp-taboola')
    </div>
<script type="application/ld+json">
    {!! json_encode($jsonLdArticle, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
