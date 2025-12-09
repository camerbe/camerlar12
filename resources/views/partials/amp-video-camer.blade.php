<div class="material-box">
    <div class="news-category">
        <p class="bg-green-dark uppercase">vidéo camer</p>
        <div class="bg-green-dark full-bottom"></div>
    </div>
    <div class="full-bottom">
        <div class="news-full">
            @php
                //dd($camer)
                $videoId=\App\Helpers\Helper::getYouTubeId($camer['video_url']);
                //dd($videoId);
            @endphp
            <amp-youtube
                data-videoid="{{$videoId}}"
                layout="responsive"
                width="480"
                height="270">
            </amp-youtube>



        </div>
    </div>
</div>

