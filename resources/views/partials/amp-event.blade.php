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
                    src="{{$event['image_url'] ?? 'https://picsum.photos/300'}}"
                    width="{{$event['imagewidth'] ?? 300}}"
                    height="{{$event['imageheight'] ?? 300}}"
                    layout="responsive">

                </amp-img>



        </div>
    </div>
</div>

