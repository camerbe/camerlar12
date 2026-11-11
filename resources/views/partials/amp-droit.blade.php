@php
    $image_width=$droit['image_width'];
    $image_height=$droit['image_height'];
    $isEmpty=strlen($image_width)<=1;
    $articleUrl =url("amp/". Illuminate\Support\Str::slug(strtolower($droit['rubrique']['rubrique'])) . '/' .
                     Illuminate\Support\Str::slug(strtolower($droit['sousrubrique']['sousrubrique'])) . '/' .
                     $droit['slug']);

    if($isEmpty){
        $image=\App\Helpers\Helper::extractImgSrc($droit['image']) ;
        $arrImgDimension=\App\Helpers\Helper::getImageDimensions($image);
        $image_height=$arrImgDimension['height'];
        $image_width=$arrImgDimension['width'];
    }



@endphp


<div class="material-box">
    <div class="news-category">
        <p class="bg-green-dark uppercase">{{$droit['sousrubrique']['sousrubrique']}}</p>
        <div class="bg-green-dark full-bottom"></div>
    </div>
    <div class="full-bottom">
        <div class="news-full">

            <a href="{{$articleUrl}}" class="news-item">
                <amp-img
                    alt="{{$droit['titre']}}"
                    title="{{$droit['titre']}}"
                    class="responsive-img"
                    src="{{$droit['image_url']}}"
                    width="{{$image_width ?? 300}}"
                    height="{{$image_height ?? 300}}"
                    layout="responsive">
                    <amp-img
                        placeholder
                        src="https://picsum.photos/300"
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

