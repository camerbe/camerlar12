
@props([
'title' => config('app.name'),
'description' => '',
'image' => '',
'image_width' => '',
'image_height' => '',
'keyword'=>'',
'modified_time'=>now()->format('Y-m-d\TH:i:s+00:00'),
'published_time'=>'',
'section'=>'',
'author'=>'',
'source '=>'',
'publisher'=>'',
'canonical' => '',
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
<meta property="og:locale:alternate" content="en-us">
<meta property="og:site_name" content="Camer.be">
<meta property="og:url" content="{{ $canonical }}">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
<meta name="twitter:image:alt" content="{{ $title }}">
<meta name="twitter:site" content="@camer.be">
<meta name="twitter:creator" content="@camer.be">
<meta name="twitter:url" content="{{$canonical}}">

<!-- Canonical -->
<link rel="canonical" href="{{ $canonical }}">
