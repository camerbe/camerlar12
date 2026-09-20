
<div class="material-box  full-top">
    @php
        $firstVideo=$videos->first();
        $videoType=$firstVideo['typevideo'];
    @endphp
    <div class="news-category">
        <p class="bg-green-dark uppercase">Dernières Vidéos de {{$videoType}}</p>
        <div class="bg-green-dark full-bottom"></div>
    </div>
@foreach($videos as $video)

    @php
        //dd($video);
        $title=$video['titre'];
        if(is_null($video['youtubeApi'])){
            $cover_image=$video['cover_image'];
            $cover_image_width=640;
            $cover_image_height=480;
        }
        else{
            $snippet=$video['youtubeApi']['snippet'] ?? [];
            $thumbnails=$snippet['thumbnails']['standard'];
            $cover_image=$thumbnails['url'];
            $cover_image_width=$thumbnails['width'];
            $cover_image_height=$thumbnails['height'];

        }
        $videoType=$video['typevideo'];
        $videoId=$video['video'];
    @endphp




        <div class="material-box">

            <div class="full-bottom">
                <div class="news-full">
                    <h5>{{$title}}</h5>
                    <amp-youtube
                        data-videoid="{{$videoId}}"
                        layout="responsive"
                        width="480"
                        height="270">
                    </amp-youtube>

                </div>
            </div>
        </div>



@endforeach

</div>
<div class="news-item">
    <nav>
        <ul class="pagination">
            @if ($videos->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" style="opacity:0.5;" ><</span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $videos->previousPageUrl() }}" class="page-link"><</a>
                </li>
            @endif

            @foreach ($videos->links()->elements[0] ?? [] as $page => $url)

                @if ($page == $videos->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{$page}}</span>
                        </li>
                @else
                        <li class="page-item">
                            <a class="page page-link" href="{{$url}}">{{$page}}</a>
                        </li>
                @endif
            @endforeach
                @if ($videos->hasMorePages())
                    <li class="page-item">
                        <a href="{{ $videos->nextPageUrl() }}" class="page page-link">></a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link" style="opacity:0.5;">></span>
                    </li>
                @endif

        </ul>
    </nav>
</div>
