@php
    $listElements = [];
@endphp
<div class="material-box  full-top">
@foreach($articles as $article)
    @php
        $today = now()->format('Y-m-d\TH:i:s+00:00');
        $articleDate = \Carbon\Carbon::parse($article['dateparution'])->format('Y-m-d\TH:i:s+00:00');
        $year = \Carbon\Carbon::parse($article['dateparution'])->year;
        $articleUrl =url("amp/". Illuminate\Support\Str::slug(strtolower($article['rubrique']['rubrique'])) . '/' .
                      Illuminate\Support\Str::slug(strtolower($article['sousrubrique']['sousrubrique'])) . '/' .
                      $article['slug']);
        $listElements[] = [
        "@type" => "ListItem",
        "position" => $loop->iteration,
        "item" => [
            "@context" => "https://schema.org",
            "@type" => "NewsArticle",
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => $articleUrl
            ],
            "headline" => $article['titre'],
            "description" => $article['chapeau'],
            "articleSection" => $article['rubrique']['rubrique'] . " / " . $article['sousrubrique']['sousrubrique'],
            "keywords" => array_map('trim', explode(',', $article['keyword'])),
            "inLanguage" => "fr-FR",
            "url" => $articleUrl,
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
        ]
    ];
    @endphp
    <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <meta itemprop="position" content="{{ $loop->iteration }}">
        <div itemscope itemtype="https://schema.org/NewsArticle">
            <link itemprop="url" href="{{ $articleUrl }}">
            <div class="amp-image-container">
            <amp-img
                itemprop="image"
                alt="{{$article['titre']}}"
                title="{{$title}}"
                src="{{$article['image_url']}}"
                width="{{$article['image_width']}}"
                height="{{$article['image_height']}}"
                layout="responsive">

            </amp-img>
            <meta itemprop="datePublished" content="{{ $articleDate }}">
            <meta itemprop="dateModified" content="{{ $today }}">
            <meta itemprop="inLanguage" content="fr-FR">
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
            <div class="material-box material-news">
                <h5 itemprop="headline" class=" uppercase news-full">{{$article['titre']}}</h5>
                <p itemprop="description" class="dropcaps-3" style="text-align: justify;">
                    {{$article['chapeau']}}
                </p>
                <div class="material-box">
                    <a href="{{$articleUrl}}" class="button bg-teal-light button-full button-round">Lire</a>
                </div>
                <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <meta itemprop="name" content="{{ $article['auteur'] }}">
                    <meta itemprop="url" content="{{url()->current()}}" />
                </span>
                <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                <meta itemprop="name" content="Camer.be">
                <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                    <meta itemprop="url" content="{{ url('assets/img/camer-logo.png') }}">
                </span>
            </span>
            </div>
        </div>
    </div>

@endforeach
</div>
<div class="news-item text-center">
    {{$articles->links()}}
</div>
@php
    $jsonLdGlobal = [
    "@context" => "https://schema.org",
    "@type" => "CollectionPage",
    "name" => $title,          // équivalent this.titleService.getTitle()
    "description" => $description, // meta description
    "url" => url()->current(),
    "mainContentOfPage" => [
        "@type" => "ItemList",
        "itemListElement" => $listElements
    ]
];
@endphp
{{-- Injection JSON-LD dans la page --}}
<script type="application/ld+json">
    {!! json_encode($jsonLdGlobal, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
