
<div class="material-box  full-top">
@foreach($articles as $article)
    @php
        $articleUrl=\App\Helpers\Helper::makeUrl(
            $article['rubrique']['rubrique'],
            $article['sousrubrique']['sousrubrique'],
            $article['slug']
        );
    @endphp





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


            </div>
                @if ($loop->iteration == 5)
                    @include('partials.gam-amp-cam-mid')
                @endif


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

