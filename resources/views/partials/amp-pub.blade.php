@php

 @endphp


@if(count($pub)>0)
    <div class="material-box">
        <div class="news-category">
            <p class="bg-green-dark uppercase">partenaire</p>
            <div class="bg-green-dark full-bottom"></div>
        </div>
        <div class="full-bottom">
            <div class="news-full">

                <amp-img
                    alt="{{$pub['endpubdate']}}"
                    title="{{$pub['endpubdate']}}"
                    src="{{$pub['image_url'] ?? 'https://picsum.photos/300'}}"
                    width="{{$pub['imagewidth']?? 300}}"
                    height="{{$pub['imageheight']?? 300}}"
                    layout="responsive">

                </amp-img>


            </div>
        </div>
    </div>
@endif

