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
    <div class="relative">
        <amp-img
            title="{{$article['titre']}}"
            alt="{{$article['titre']}}"
            class="full-bottom"
            src="{{$article['image_url']}}"
            width="{{$article['image_width'] ?? 300}}"
            height="{{$article['image_height'] ?? 300}}"
            layout="responsive">
            <amp-img
                placeholder
                src="https://picsum.photos/"
                width="{{ $article['image_width'] ?? 300 }}"
                height="{{ $article['image_height'] ?? 300 }}">
            </amp-img>

        </amp-img>
        <div class="absolute badge"
            style="bottom: 60px; right: -5px;"><i class="fa fa-bolt" aria-hidden="true"></i>
            {{$article['sousrubrique']['sousrubrique']}}
        </div>
    </div>
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

        <div  class="footer-socials">
            <div class="social-icons social-round">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($articleUrl) }}"
                   target="_blank"
                   class="facebook-bg"
                   aria-label="Partager sur Facebook"
                   rel="noopener nofollow">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M240 363.3L240 576L356 576L356 363.3L442.5 363.3L460.5 265.5L356 265.5L356 230.9C356 179.2 376.3 159.4 428.7 159.4C445 159.4 458.1 159.8 465.7 160.6L465.7 71.9C451.4 68 416.4 64 396.2 64C289.3 64 240 114.5 240 223.4L240 265.5L174 265.5L174 363.3L240 363.3z"/></svg>
                </a>
            </div>
            <div class="social-icons social-round" >
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($articleUrl) }}"
                   target="_blank"
                   class="twitter-bg"
                   aria-label="Partager sur X"
                   rel="noopener nofollow">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 640 640">
                        <!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path d="M453.2 112L523.8 112L369.6 288.2L551 528L409 528L297.7 382.6L170.5 528L99.8 528L264.7 339.5L90.8 112L236.4 112L336.9 244.9L453.2 112zM428.4 485.8L467.5 485.8L215.1 152L173.1 152L428.4 485.8z"/>
                    </svg>
                </a>
            </div>
            <div class="social-icons social-round" >
                <a href="https://api.whatsapp.com/send?text={{ urlencode($article['titre']) }}%20{{ urlencode($articleUrl) }}"
                   target="_blank"
                   class="whatsapp-bg"
                   aria-label="Partager sur X"
                   rel="noopener nofollow">
                    <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M476.9 161.1C435 119.1 379.2 96 319.9 96C197.5 96 97.9 195.6 97.9 318C97.9 357.1 108.1 395.3 127.5 429L96 544L213.7 513.1C246.1 530.8 282.6 540.1 319.8 540.1L319.9 540.1C442.2 540.1 544 440.5 544 318.1C544 258.8 518.8 203.1 476.9 161.1zM319.9 502.7C286.7 502.7 254.2 493.8 225.9 477L219.2 473L149.4 491.3L168 423.2L163.6 416.2C145.1 386.8 135.4 352.9 135.4 318C135.4 216.3 218.2 133.5 320 133.5C369.3 133.5 415.6 152.7 450.4 187.6C485.2 222.5 506.6 268.8 506.5 318.1C506.5 419.9 421.6 502.7 319.9 502.7zM421.1 364.5C415.6 361.7 388.3 348.3 383.2 346.5C378.1 344.6 374.4 343.7 370.7 349.3C367 354.9 356.4 367.3 353.1 371.1C349.9 374.8 346.6 375.3 341.1 372.5C308.5 356.2 287.1 343.4 265.6 306.5C259.9 296.7 271.3 297.4 281.9 276.2C283.7 272.5 282.8 269.3 281.4 266.5C280 263.7 268.9 236.4 264.3 225.3C259.8 214.5 255.2 216 251.8 215.8C248.6 215.6 244.9 215.6 241.2 215.6C237.5 215.6 231.5 217 226.4 222.5C221.3 228.1 207 241.5 207 268.8C207 296.1 226.9 322.5 229.6 326.2C232.4 329.9 268.7 385.9 324.4 410C359.6 425.2 373.4 426.5 391 423.9C401.7 422.3 423.8 410.5 428.4 397.5C433 384.5 433 373.4 431.6 371.1C430.3 368.6 426.6 367.2 421.1 364.5z"/></svg>
                </a>
            </div>
        </div>
        <section>
        <h3>Commentaires</h3>

            <amp-iframe
                width="600" height="400"
                src="https://camer-be.disqus.com/embed/comments#{{$article['id']}}"
                layout="responsive"
                sandbox="allow-scripts allow-same-origin allow-modals allow-popups allow-forms"
                frameborder="0"
            >

            </amp-iframe>


        </section>

    @include('partials.amp-samerubrique')

        @include('partials.amp-taboola')
    </div>
<script type="application/ld+json">
    {!! json_encode($jsonLdArticle, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
