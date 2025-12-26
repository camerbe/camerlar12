<div class="material-box">
    <div class="news-category">
        <p class="bg-green-dark uppercase">{{$droit['sousrubrique']['sousrubrique']}}</p>
        <div class="bg-green-dark full-bottom"></div>
    </div>
    <div class="full-bottom">
        <div class="news-full">
            @php
                //dd($droit);
                $articleUrl =url("amp/". Illuminate\Support\Str::slug(strtolower($droit['rubrique']['rubrique'])) . '/' .
                     Illuminate\Support\Str::slug(strtolower($droit['sousrubrique']['sousrubrique'])) . '/' .
                     $droit['slug']);
            @endphp
            <a href="{{$articleUrl}}" class="news-item">
                <amp-img
                    alt="{{$droit['titre']}}"
                    title="{{$droit['titre']}}"
                    class="responsive-img"
                    src="{{$droit['image_url']}}"
                    width="{{$droit['image_width']}}"
                    height="{{$droit['image_height']}}"
                    layout="responsive">
                    <amp-img
                        placeholder
                        src="https://picsum.photos/"
                        width="{{ $article['image_width'] ?? 300 }}"
                        height="{{ $article['image_height'] ?? 300 }}">
                    </amp-img>
                </amp-img>

            </a>
            <div class="material-box">
                <a href="{{$articleUrl}}" class="button bg-teal-light button-full button-round uppercase">Rubrique -> {{$droit['sousrubrique']['sousrubrique']}}</a>
            </div>

        </div>
    </div>
</div>

