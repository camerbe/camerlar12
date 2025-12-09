<div class="decoration decoration-margins"></div>
<div class="material-box">
    <div class="news-category">
        <p class="bg-green-dark uppercase">Lire aussi</p>
        <div class="bg-green-dark full-bottom"></div>
    </div>
    <div class="full-bottom">
        <div class="news-thumbs">
            @foreach($samerubriques as $article)
                @php
                    $articleUrl =url("amp/". Illuminate\Support\Str::slug(strtolower($article['rubrique']['rubrique'])) . '/' .
                      Illuminate\Support\Str::slug(strtolower($article['sousrubrique']['sousrubrique'])) . '/' .
                      $article['slug']);
                @endphp
                <a href="{{$articleUrl }}" class="news-item">
                    <amp-img
                        alt="{{$article['titre']}}"
                        title="{{$title}}"
                        class="responsive-img"
                        src="{{$article['image_url']}}"
                        width="{{$article['image_width']}}"
                        height="{{$article['image_height']}}"
                        layout="responsive">

                    </amp-img>
                    <h5 style="font-size: x-small;">{{$article['titre']}}</h5>
                </a>
            @endforeach
        </div>
    </div>
</div>
