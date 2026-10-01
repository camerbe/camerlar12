@php

    $pubIframe=$iframe[0];
    $pub=\App\Helpers\Helper::extractFromParagraph($pubIframe['pub']) ?? null;
    $titre=$pubIframe["editor"];
    $isPub = !is_null($pub);
@endphp

@if($isPub)
<div class="material-box">
    <div class="news-category">
        <p class="bg-green-dark uppercase">{{$titre}}</p>
        <div class="bg-green-dark full-bottom"></div>
    </div>
    <div class="full-bottom">
        <div class="news-full">
            <amp-iframe
                width="600"
                height="400"
                layout="responsive"
                sandbox="allow-scripts allow-same-origin"
                frameborder="0"
                src="{{$pub}}">
            </amp-iframe>



        </div>
    </div>
</div>
@endif
