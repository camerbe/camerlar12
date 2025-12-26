<div class="material-box">
    <div class="news-category">
        <p class="bg-green-dark uppercase">{{$debat['sousrubrique']['sousrubrique']}}</p>
        <div class="bg-green-dark full-bottom"></div>
    </div>
    <div class="full-bottom">
        <div class="news-full">
            @php
                //dd($debat);
                $articleUrl =url("amp/". Illuminate\Support\Str::slug(strtolower($debat['rubrique']['rubrique'])) . '/' .
                     Illuminate\Support\Str::slug(strtolower($debat['sousrubrique']['sousrubrique'])) . '/' .
                     $debat['slug']);
            @endphp
           <a href="{{$articleUrl}}" class="news-item">
               <amp-img
                   alt="{{$debat['titre']}}"
                   title="{{$debat['titre']}}"
                   class="responsive-img"
                   src="{{$debat['image_url']}}"
                   width="{{$debat['image_width'] ?? 300}}"
                   height="{{$debat['image_height'] ?? 300}}"
                   layout="responsive">
                   <amp-img
                       placeholder
                       src="https://picsum.photos/"
                       width="{{ $article['image_width'] ?? 300 }}"
                       height="{{ $article['image_height'] ?? 300 }}">
                   </amp-img>
               </amp-img>

           </a>


        </div>
    </div>
</div>
