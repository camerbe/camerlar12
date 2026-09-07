
    @php
        $listElements = [];
        $firstArticle=$articles->first();

    @endphp
    <div class="material-box  full-top">
        <div class="news-category">
            <p class="bg-green-dark uppercase">{{$firstArticle['sousrubrique']['sousrubrique']}}</p>
            <div class="bg-green-dark full-bottom"></div>
        </div>
        @foreach($articles as $article)
            @php
                $today = now()->format('Y-m-d\TH:i:s+00:00');
                $articleDate = \Carbon\Carbon::parse($article['dateparution'])->format('Y-m-d\TH:i:s+00:00');
                $year = \Carbon\Carbon::parse($article['dateparution'])->year;
                $articleUrl =url("amp/". Illuminate\Support\Str::slug(strtolower($article['rubrique']['rubrique'])) . '/' .
                              Illuminate\Support\Str::slug(strtolower($article['sousrubrique']['sousrubrique'])) . '/' .
                              $article['slug']);

            @endphp
            <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <meta itemprop="position" content="{{ $loop->iteration }}">
                <div itemscope itemtype="https://schema.org/NewsArticle">
                    <link itemprop="url" href="{{ $articleUrl }}">
                    <div class="amp-image-container">
                        <amp-img
                            itemprop="image"
                            alt="{{$article['titre']}}"
                            title="{{$title}}"
                            src="{{$article['image_url']}}"
                            width="{{$article['image_width'] ?? 300}}"
                            height="{{$article['image_height'] ?? 300}}"
                            layout="responsive">
                            <amp-img
                                placeholder
                                src="https://picsum.photos/300"
                                width="{{ $article['image_width'] ?? 300 }}"
                                height="{{ $article['image_height'] ?? 300 }}">
                            </amp-img>
                        </amp-img>
                        <meta itemprop="datePublished" content="{{ $articleDate }}">
                        <meta itemprop="dateModified" content="{{ $today }}">
                        <meta itemprop="inLanguage" content="fr-FR">
                        <div itemprop="contentLocation" itemscope itemtype="https://schema.org/Place">
                            <meta itemprop="name" content="{{$article['countries']['pays']}}">
                        </div>
                        <div itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">
                            <meta itemprop="interactionType" content="https://schema.org/ReadAction" />
                            <meta itemprop="userInteractionCount" content="{{$article['hit']}}" />
                        </div>

                        <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                            <meta itemprop="url" content="{{$article['image_url']}}" />
                            <meta itemprop="width" content="{{$article['image_width']}}" />
                            <meta itemprop="height" content="{{$article['image_height']}}" />
                        </div>
                        <meta itemprop="keywords" content="{{$article['keyword']}}" />
                        <meta itemprop="articleSection" content="{{$article['rubrique']['rubrique']}} / {{$article['sousrubrique']['sousrubrique']}}" />
                        <div class="material-box material-news">
                            <h5 itemprop="headline" class=" uppercase news-full">{{$article['titre']}}</h5>
                            <p itemprop="description" class="dropcaps-3" style="text-align: justify;">
                                {{$article['chapeau']}}
                            </p>
                            <div class="material-box">
                                <a href="{{$articleUrl}}" class="button bg-teal-light button-full button-round">Lire</a>
                            </div>
                            <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <meta itemprop="name" content="{{ $article['auteur'] }}">
                        <meta itemprop="url" content="{{url()->current()}}" />
                    </span>
                            <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                    <meta itemprop="name" content="Camer.be">
                    <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url" content="{{ url('assets/img/camer-logo.png') }}">
                    </span>
                </span>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
            <div class="news-item">
                <nav>
                    <ul class="pagination">
                        @if ($articles->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" style="opacity:0.5;" ><</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $articles->previousPageUrl() }}" class="page-link"><</a>
                            </li>
                        @endif

                        @foreach ($articles->links()->elements[0] ?? [] as $page => $url)

                            @if ($page == $articles->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{$page}}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page page-link" href="{{$url}}">{{$page}}</a>
                                </li>
                            @endif
                        @endforeach
                        @if ($articles->hasMorePages())
                            <li class="page-item">
                                <a href="{{ $articles->nextPageUrl() }}" class="page page-link">></a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" style="opacity:0.5;">></span>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>

    </div>


