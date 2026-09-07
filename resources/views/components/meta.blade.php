@props([
    'title' => config('app.name'),
    'description' => '',
    'image' => '',
    'image_width' => '',
    'image_height' => '',
    'image_type' => '',
    'keyword' => '',
    'modified_time' => '',
    'published_time' => '',
    'section' => '',
    'author' => '',
    'source' => '',
    'publisher' => '',
    'canonical' => '',
    'ampcanonical' => '',
    'georegion' => '',
    'geoplacename' => '',
    'hashtags' => [],
    'isJson4article' => '',
    'isJson4listItem' => '',
    'isVideo' => '',
    'rub' => '',
    'sousrub' => '',
    'breadcumbUrl' => '',
    'wordCount' => 0,
    'hit' => 0,
    'info',
    'listElements'=>[],
    'listItemVideos'=>[],
    'jld'=>'[]',
])

<!-- Primary Meta Tags -->
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keyword }}">
<meta name="article:modified_time" content="{{ $modified_time }}">
<meta name="article:published_time" content="{{ $published_time }}">
<meta name="article:section" content="{{ $section }}">
<meta name="article:author" content="{{ $author }}">
<meta name="article:publisher" content="{{ $source }}">

<!-- Open Graph / Facebook -->
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:alt" content="{{ $title }}">
<meta property="og:image:height" content="{{ $image_height }}">
<meta property="og:image:width" content="{{ $image_width }}">
<meta property="og:type" content="article">
<meta property="og:locale" content="fr_FR">
<meta property="og:locale:alternate" content="en_US">
<meta property="og:site_name" content="Camer.be">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image:type" content="{{ $image_type }}">
<meta property="og:image:secure_url" content="{{ $image }}">

@if(count($hashtags) > 0)
    @foreach($hashtags as $hashtag)
<meta property="og:tag" content="{{ $hashtag }}">
    @endforeach
@endif

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
<meta name="twitter:image:alt" content="{{ $title }}">
<meta name="twitter:site" content="@camer.be">
<meta name="twitter:creator" content="@camer.be">
<meta name="twitter:url" content="{{ $canonical }}">

{{-- GEO --}}
<meta name="geo.region" content="{{ $georegion }}">
<meta name="geo.placename" content="{{ $geoplacename }}">
<meta name="language" content="fr">

<meta name="author" content="{{ $author }}">
<link rel="publisher" href="https://www.camer.be">

<!-- Canonical -->
<link rel="canonical" href="{{ $canonical }}">
@if($ampcanonical)
<link rel="amphtml" href="{{ $ampcanonical }}">
@endif
<link rel="preload" as="image" href="{{ $image }}" fetchpriority="high">

{{-- JSON-LD --}}
@if($isJson4article)

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "Accueil",
                "item": "https://www.camer.be/"
            },
            {
                "@@type": "ListItem",
                "position": 2,
                "name": "{{$rub}}"
             },
             {  "@@type": "ListItem",
                "position": 3,
                "name": "{{$sousrub}}",
                "item": "{{config('app.url')}}/{{$breadcumbUrl}}"
              }
        ]
        }
    </script>

    <script type="application/ld+json">
        {!! json_encode($jld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

@endif

@if($isJson4listItem)
    <script type="application/ld+json">
        {!! json_encode($listElements, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

@endif
@if($isVideo)
    <script type="application/ld+json">
        {!! json_encode($listItemVideos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

@endif

