@if(count($archives)>0)
    <div class="material-box">
        @php
            $labels = [
                'week'  => 'il y a une semaine',
                'month' => 'il y a un mois',
                'year'  => 'il y a un an',
            ];
        @endphp
        @foreach($archives as $periods=>$item)

            @if(count($item['data'])>0)

                <div class="news-category">
                    <p class="bg-green-dark uppercase">{{$labels[$periods]}}</p>
                    <div class="bg-green-dark full-bottom"></div>
                </div>
                <div class="full-bottom">
                    <div class="news-thumbs">
                        @foreach($item['data'] as $article)
                            @php
                                $articleUrl =url("amp/". Illuminate\Support\Str::slug(strtolower($article['rubrique']['rubrique'])) . '/' .
                                  Illuminate\Support\Str::slug(strtolower($article['sousrubrique']['sousrubrique'])) . '/' .
                                  $article['slug']);
                            @endphp
                            <a href="{{$articleUrl }}" class="news-item">
                                <amp-img
                                    alt="{{$article['titre']}}"
                                    title="{{$article['titre']}}"
                                    class="responsive-img"
                                    src="{{$article['image_url']}}"
                                    width="{{$article['image_width'] ?? 300}}"
                                    height="{{$article['image_height'] ?? 300}}"
                                    layout="responsive">

                                </amp-img>
                                <h5 style="font-size: x-small;">{{$article['titre']}}</h5>
                            </a>


                        @endforeach

                    </div>
                </div>
            @endif
        @endforeach

    </div>
@endif
