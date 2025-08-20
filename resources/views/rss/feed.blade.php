{{-- resources/views/rss/feed.blade.php --}}
{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>Le flux rss de camer.be</title>
        <link>{{ url('/')  }}/rss</link>
        <description><![CDATA[Camer.be, l'info claire et nette]]></description>
        <language>fr-FR</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ url('/') }}/rss" rel="self" type="application/rss+xml" />
        <atom:link href="https://pubsubhubbub.appspot.com/" rel="hub"/>
        @foreach($items as $item)
            @php
                $image= App\Helpers\Helper::extractImgSrc($item->image);
                $image= App\Helpers\Helper::parseImageUrl($image);
                $media = $item->getMedia('article')->where('name',$item->slug)->first();
                $auteur=str_replace("&", "et", $item->auteur);
                //dd($media->getUrl());
                /*->toMediaCollection('article');
                 $mimeType = $media->mime_type;
                 $sizeInBytes =$media->size;
                $response = Http::head($image);
                $mimeType = $response->header('Content-Type');
                $sizeInBytes = $response->header('Content-Length');*/
                $titre=App\Helpers\Helper::getTitle($item->countries->pays,$item->titre,$item->countries->country);
            @endphp
            <item>
                <title><![CDATA[{{ $titre }}]]></title>
                <link>{{ url('/' . $item->slug) }}</link>
                <description>
                    <![CDATA[
                        <p>{!! $item->chapeau !!}</p>
                        @if($media)
                            <p><img src="{{ $media->getUrl() }}" alt="{{ $titre }}" width="600"/></p>
                        @endif
                        ]]>
                </description>
                <guid isPermaLink="true">{{ url('/' . $item->slug) }}</guid>
                <content:encoded><![CDATA[{!! $item->info ?? $item->chapeau !!}]]></content:encoded>
                <pubDate>{{ \Carbon\Carbon::parse($item->dateparution)->toRssString()}}</pubDate>
                @if($item->auteur)
                    <author>{{$auteur}}</author>
                @endif
                @if($item->sousrubrique)
                    <category>{{ $item->sousrubrique->sousrubrique }}</category>
                @endif
                @if($media)
                    <enclosure url="{{ $media->getUrl() }}" type="{{$media->mime_type}}" length="{{$media->size}}" />
                    <media:thumbnail url="{{ $media->getUrl() }}" />
                @endif
            </item>
        @endforeach
</channel>
</rss>
