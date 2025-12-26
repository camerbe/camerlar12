<div class="material-box">
    <div class="news-category">
        <p class="bg-green-dark uppercase">évènement</p>
        <div class="bg-green-dark full-bottom"></div>
    </div>
    <div class="full-bottom">
        <div class="news-full">


                <amp-img
                    alt="{{$event['eventdate']}}"
                    title="{{$event['eventdate']}}"
                    class="responsive-img"
                    src="{{$event['image_url']}}"
                    width="{{$event['imagewidth']}}"
                    height="{{$event['imageheight']}}"
                    layout="responsive">
                    <amp-img
                        placeholder
                        src="https://picsum.photos/"
                        width="{{ $event['imagewidth'] ?? 300 }}"
                        height="{{ $event['imageheight'] ?? 300 }}">
                    </amp-img>
                </amp-img>



        </div>
    </div>
</div>

