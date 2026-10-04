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

                $auteur=str_replace("&", "et", $item["auteur"]);
                $rub=Illuminate\Support\Str::slug($item["rubrique"]["rubrique"]);
                $sousrub=Illuminate\Support\Str::slug($item["sousrubrique"]["sousrubrique"]);
                $img=$item["image_url"];
                $mimeType = $item["image_mimetype"];
                $titre=App\Helpers\Helper::getTitle($item["countries"]["pays"],$item["titre"],$item["countries"]["country"]);
            @endphp
            <item>
                <title><![CDATA[{{ $titre }}]]></title>
                <link>{{ url('/' . $rub.'/'.$sousrub.'/'.$item["slug"]) }}</link>
                <description>
                    <![CDATA[
                    <p>{!! $item["chapeau"] !!}</p>
                    @if($mimeType)
                        <p><img src="{{ $img }}" alt="{{ $titre }}" width="600"/></p>
                    @endif
                    ]]>
                </description>
                <guid isPermaLink="true">{{ url('/' . $rub.'/'.$sousrub.'/'.$item["slug"]) }}</guid>
                <content:encoded><![CDATA[{!! $item["info"] ?? $item["chapeau"] !!}]]></content:encoded>
                <pubDate>{{ \Carbon\Carbon::parse($item["dateparution"])->toRssString()}}</pubDate>
                @if($auteur)
                    <author>{{$auteur}}</author>
                @endif
                @if($item["sousrubrique"])
                    <category>{{ $item["sousrubrique"]["sousrubrique"] }}</category>
                @endif
                @if($mimeType)
                    <enclosure url="{{ $img }}" type="{{$mimeType}}" length="{{rand(1000, 13000)}}" />
                    <media:thumbnail url="{{ $img }}" />
                @endif
            </item>
        @endforeach
</channel>
</rss>
