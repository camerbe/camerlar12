@if(!$isVideo)
    @php
        $listElements = [];
        $firstArticle=$articles->first();
        //dd($firstArticle);
    @endphp
    <div class="material-box  full-top">
        <div class="news-category">
            <p class="bg-green-dark uppercase">{{$firstArticle['sousrubrique']['sousrubrique']}}</p>
            <div class="bg-green-dark full-bottom"></div>
        </div>
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
                            width="{{$article['image_width'] ?? 300}}"
                            height="{{$article['image_height'] ?? 300}}"
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
            <div class="news-item">
                <nav>
                    <ul class="pagination">
                        @if ($articles->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" style="opacity:0.5;" ><</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $articles->previousPageUrl() }}" class="page-link"><</a>
                            </li>
                        @endif

                        @foreach ($articles->links()->elements[0] ?? [] as $page => $url)

                            @if ($page == $articles->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{$page}}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page page-link" href="{{$url}}">{{$page}}</a>
                                </li>
                            @endif
                        @endforeach
                        @if ($articles->hasMorePages())
                            <li class="page-item">
                                <a href="{{ $articles->nextPageUrl() }}" class="page page-link">></a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" style="opacity:0.5;">></span>
                            </li>
                        @endif

                    </ul>
                </nav>
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
    </div>
@else
                @php
                    $listElements = [];
                    $firstArticle=$articles->first();
                    //dd($firstArticle);
                @endphp
                <div class="material-box  full-top">

                    <div class="news-category">
                        <p class="bg-green-dark uppercase">{{$firstArticle['typevideo']}}</p>
                        <div class="bg-green-dark full-bottom"></div>
                    </div>

                @foreach($articles as $article)
                    <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <meta itemprop="position" content="{{ $loop->iteration }}">
                        <div itemscope itemtype="https://schema.org/VideoObject">
                            <link itemprop="url" href="{{ $article["video_url"] }}">
                            @php
                                $idx=$loop->iteration*-1;
                                $youtubeId=\App\Helpers\Helper::getYouTubeId($article['video']);
                                $published_at=\Carbon\Carbon::  now()->addDay($idx)->format('Y-m-d\TH:i:s+00:00')
                            @endphp
                            <meta itemprop="uploadDate" content="{{ $published_at }}">
                            <div class="amp-image-container">
                                <div class="material-box material-news">

                                    <amp-youtube
                                        data-videoid="{{ $youtubeId}}"
                                        layout="responsive"
                                        width="480"
                                        height="270">
                                    </amp-youtube>
                                    <h5 itemprop="headline"  class=" uppercase news-full">{{$article['titre']}}</h5>
                                </div>

                                <meta itemprop="name" content="{{ $article['titre'] }}">
                                <meta itemprop="description" content="{{ $article['titre'] }}">

                                <meta itemprop="inLanguage" content="fr-FR">
                                <meta itemprop="thumbnailUrl" content="{{$article['cover_image']}}">
                                <meta itemprop="embedUrl" content="https://www.youtube.com/embed/{{$youtubeId}}">
                                <meta itemprop="contentUrl" content="https://youtu.be/{{$youtubeId}}">

                                <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                                    <meta itemprop="name" content="{{ $article['typevideo'] }}">
                                </span>

                                <!-- Publisher -->
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
                    <div class="news-item">
                        <nav>
                            <ul class="pagination">
                                @if ($articles->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link" style="opacity:0.5;" ><</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ $articles->previousPageUrl() }}" class="page-link"><</a>
                                    </li>
                                @endif

                                @foreach ($articles->links()->elements[0] ?? [] as $page => $url)

                                    @if ($page == $articles->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{$page}}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page page-link" href="{{$url}}">{{$page}}</a>
                                        </li>
                                    @endif
                                @endforeach
                                @if ($articles->hasMorePages())
                                    <li class="page-item">
                                        <a href="{{ $articles->nextPageUrl() }}" class="page page-link">></a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link" style="opacity:0.5;">></span>
                                    </li>
                                @endif

                            </ul>
                        </nav>
                    </div>
                </div>
                @php
                    $jsonLd = [
                        "@context" => "https://schema.org",
                        "@type" => "CollectionPage",
                        "name" => $title,
                        "description" => $description,
                        "url" => url()->current(),
                        "mainContentOfPage" => [
                            "@type" => "ItemList",
                            "itemListElement" => $articles->map(function ($video, $index) {
                                return [
                                    "@type" => "VideoObject",
                                    "position" => $index + 1,
                                    "name" => $video['titre'],
                                    "description" => $video['titre'],
                                    "thumbnailUrl" => $video['cover_image'],
                                    "embedUrl" =>  $video['video_url'],
                                    "contentUrl" => $video['video_url'],
                                    "url" => $video['video_url'],
                                    "uploadDate" =>\Carbon\Carbon::  now()->addDay(-$index)->format('Y-m-d\TH:i:s+00:00'),
                                ];
                            })
                        ]
                    ];
                @endphp

                <script type="application/ld+json">
                    {!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}
                </script>


@endif
