
<div class="material-box  full-top">
@foreach($videos as $video)






            <div class="amp-image-container">
                <amp-img
                    itemprop="image"
                    alt="{{$article['titre']}}"
                    title="{{$title}}"
                    src="{{$article['image_url']}}"
                    width="{{$article['image_width'] ?? 300}}"
                    height="{{$article['image_height'] ?? 300}}"
                    layout="responsive">
                    <amp-img
                        placeholder
                        src="https://picsum.photos/300"
                        width="{{ $article['image_width'] ?? 300 }}"
                        height="{{ $article['image_height'] ?? 300 }}">
                    </amp-img>
                </amp-img>





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
