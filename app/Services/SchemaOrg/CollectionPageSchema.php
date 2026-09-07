<?php

namespace App\Services\SchemaOrg;
use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Html2Text\Html2Text;
class CollectionPageSchema
{
    /** * Construit le Schema.org d'une page de collection d'articles. */
     public function articles(array $articles): array
     {
         if (empty($articles)) {
             return [
                 '@context' => 'https://schema.org',
                 '@type' => 'CollectionPage',
                 'name' => 'Actualités Cameroun | Camer.be',
                 'url' =>  $this->canonicalCurrentUrl(),
                 'mainContentOfPage' => [ '@type' => 'ItemList',
                     'itemListElement' => [],
                     ],
                 ];
        }
         $items = $this->getArticleItems($articles);
         $rubrique = $articles[0]['sousrubrique']['sousrubrique'] ?? 'Actualités';
         $description = $this->buildCollectionDescription( 'Camer.be: Info claire et nette sur le Cameroun et la Diaspora.', $articles[0]['titre'] ?? '' );
         return [
             '@context' => 'https://schema.org',
             '@type' => 'CollectionPage',
             'name' => sprintf( 'Actualités Cameroun, Info & Analyse – Sport, %s | Camer.be', $rubrique ),
             'description' => $description,
             'url' => $this->canonicalCurrentUrl(),
             'mainContentOfPage' => [
                 '@type' => 'ItemList',
                 'itemListElement' => $items,
                 ],
             ];
     }
     /** * Génère les éléments ItemList pour les articles. */
    private function getArticleItems(array $articles): array
    {
        $items = [];
        foreach ($articles as $index => $article)
        {
            //$url = Helper::makeUrl( $article['rubrique']['rubrique'] ?? '', $article['sousrubrique']['sousrubrique'] ?? '', $article['slug'] ?? '' );
            $image = $article['image_url'] ?? 'https://www.camer.be/assets/img/logo.png';
            $url = $this->articleUrl($article);
            $description = Str::limit( strip_tags($article['chapeau'] ?? ''), 155 );
            $keywords = Helper::getRealKeywords( $article['keyword'] ?? '' );
            $title = Helper::getTitle( $article['countries']['pays'] ?? '', $article['titre'] ?? '', $article['countries']['country'] ?? '' );
            $html = new Html2Text( $article['info'] ?? '' );
            $articleBody = $html->getText();
            $wordCount = str_word_count( $articleBody );
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'NewsArticle',
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $url,
                        ],
                    'headline' => $title,
                    "isPartOf"=> [
                        "@type" => "WebSite",
                        "name"=>  "Camer.be",
                        "url"=>  "https://www.camer.be"
                    ],
                    'description' => $description,
                    'articleSection' => Str::title( $article['sousrubrique']['sousrubrique'] ?? '' ),
                    'keywords' => explode(',', $keywords),
                    'inLanguage' => 'fr-FR',
                    'url' => $url,
                    'datePublished' => $this->toIsoDate( $article['dateparution'] ?? null ),
                    'dateModified' => $this->toIsoDate( now()?? null ),
                    'isAccessibleForFree' => true,
                    'copyrightYear' => $this->getYear( $article['dateparution'] ?? null ),
                    "copyrightHolder"=> [
                        "@type"=> "Organization",
                        "name"=> "Camer.be"
                    ],
                    'author' => [
                        '@type' => 'Person',
                        'name' => $article['auteur'] ?? 'Camer.be',
                        'url' => rtrim(config('app.url'), '/') . '/auteur/' . Str::slug($article['auteur'] ?? ''),
                        ],
                    'editor' => [
                        '@type' => 'Person',
                        'name' => $article['source'] ?? 'Camer.be',
                        ],
                    'publisher' => $this->publisher(),
                    "thumbnailUrl"=> "{$image}",
                    'image' => $this->articleImage($article, $title),
                    'contentLocation' => [
                        '@type' => 'Place',
                        'name' => Str::title( $article['countries']['pays'] ?? 'Cameroun' ),
                        ],
                    'articleBody' => $articleBody,
                    'wordCount' => $wordCount,
                    'interactionStatistic' => [
                        [
                            '@type' => 'InteractionCounter',
                            'interactionType' => [
                                '@type' => 'ReadAction',
                                ],
                            'userInteractionCount' => (int) ( $article['hit'] ?? 0 ),
                            ],
                        ],
                    'sameAs' => [
                        'https://www.facebook.com/camergroup',
                        'https://x.com/camerbe',
                        ],
                    ],
                ];
        }
        return $items;
    }
    /** * Génère le Schema.org NewsArticle d'un article.
     * * @param array $article
     * @return array */
    public function newsArticle(array $article): array
    {
        /* |--------------------------------------------------------------------------
        | URL canonique
        |--------------------------------------------------------------------------
        */
        $url = $this->articleUrl($article);
        /* |--------------------------------------------------------------------------
        | Titre
        |--------------------------------------------------------------------------
        */
        $title = Helper::getTitle( $article['countries']['pays'] ?? '',
            $article['titre'] ?? '',
            $article['countries']['country'] ?? ''
        );
        /* |--------------------------------------------------------------------------
        | Description
        |--------------------------------------------------------------------------
        */
        $description = Str::limit(
            preg_replace(
                '/\s+/',
                ' ',
                trim(strip_tags($article['chapeau'] ?? '')) ),
            155
            );
        /*
        |--------------------------------------------------------------------------
        | Rubrique
        |--------------------------------------------------------------------------
        */
        $sousrub = Str::title( $article['sousrubrique']['sousrubrique'] ?? '' );
        /*
        |--------------------------------------------------------------------------
        | Mots-clés
        |--------------------------------------------------------------------------
        */
        $keywords = Helper::getRealKeywords( $article['keyword'] ?? '' );
        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */
        $publishedTime = $this->toIsoDate( $article['dateparution'] ?? null );
        /*
        | Si tu possèdes une vraie date de modification dans ta BDD,
        | utilise-la ici à la place de dateparution.
        */
        $modifiedTime = $this->toIsoDate( now() ?? null );
        /*
        |--------------------------------------------------------------------------
        | Auteur
        |--------------------------------------------------------------------------
        */
        $author = trim( $article['auteur'] ?? 'Camer.be' );
        $authorUrl = $this->appUrl() . "/auteur/{$author}";
        /*
        |--------------------------------------------------------------------------
        | Éditeur / source
        |--------------------------------------------------------------------------
        */
        $source = trim( $article['source'] ?? 'Camer.be' );
        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */
        $image = $article['image_url'] ?? 'https://www.camer.be/assets/img/logo.png';
        $imageHeight = (int) ( $article['image_height'] ?? 675 );
        $imageWidth = (int) ( $article['image_width'] ?? 1200 );
        /*
        |--------------------------------------------------------------------------
        | Localisation
        |--------------------------------------------------------------------------
        */
        $geoPlaceName = Str::title( $article['countries']['pays'] ?? 'Cameroun' );
        /*
        |--------------------------------------------------------------------------
        | Corps de l'article
        |--------------------------------------------------------------------------
        |
        | On supprime le HTML afin de fournir du texte brut
        | dans articleBody.
        |
        */
        $html = new Html2Text( $article['info'] ?? '' );
        $articleBody = trim( preg_replace( "/[ \t]+/", ' ', $html->getText() ) );
        /*
        |--------------------------------------------------------------------------
        | Nombre de mots
        |--------------------------------------------------------------------------
        */
        $wordCount = str_word_count( $articleBody );
        /*
        |--------------------------------------------------------------------------
        | Nombre de lectures
        |--------------------------------------------------------------------------
        */
        $hit = (int) ( $article['hit'] ?? 0 );
        /*
            |--------------------------------------------------------------------------
            | NewsArticle
            |--------------------------------------------------------------------------
            */
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $url,
            ],
            'headline' => $title,
            "isPartOf"=> [
                "@type" => "WebSite",
                "name"=>  "Camer.be",
                "url"=>  "https://www.camer.be"
            ],
            'description' => $description,
            'articleSection' => $sousrub,
            'inLanguage' => 'fr-FR',
            'keywords' =>  explode(',', $keywords),
            'url' => $url,
            'datePublished' => $publishedTime,
            'dateModified' => $modifiedTime,
            'isAccessibleForFree' => true,
            'copyrightYear' => $publishedTime ? Carbon::parse($publishedTime)->year : null,
            "copyrightHolder"=> [
                "@type"=> "Organization",
                "name"=> "Camer.be"
            ],
            'editor' => [
                '@type' => 'Person',
                'name' => $source,
            ],
            "thumbnailUrl"=> "{$image}",
            'image' => [
                [
                    '@type' => 'ImageObject',
                    'url' => $image,
                    'height' => $imageHeight,
                    'width' => $imageWidth,
                    'caption' => $title,
                ],
            ],
            'contentLocation' =>
                [
                    '@type' => 'Place',
                    'name' => $geoPlaceName,
                ],
            'articleBody' => $articleBody,
            'wordCount' => $wordCount,
            'interactionStatistic' => [
                [
                    '@type' => 'InteractionCounter',
                    'interactionType' =>
                        [
                            '@type' => 'ReadAction',
                        ],
                    'userInteractionCount' => $hit,
                ],
            ],
            'author' => [
                '@type' => 'Person',
                'name' => $author,
                'url' => $authorUrl,
                'worksFor' => [
                    '@type'=> "Organization",
                    'name'=> "Camer.be",
                    'url'=> "https://www.camer.be",
                ]
            ],
            'publisher' => $this->publisher(),
        ];
    }
    /** * Génère le JSON-LD d'une page de vidéos. */
    public function videos(array $videos): array
    {
        if (empty($videos))
        {
            $url=url()->current();
            return [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Vidéos Cameroun | Camer.be',
                'url' => url()->current(),
                'mainContentOfPage' => [
                    '@type' => 'ItemList',
                    'itemListElement' => [],
                    ],
                ];
        }
        $url=url()->current();
        $items = $this->getVideoItems($videos);
        $description = $this->buildCollectionDescription( 'Camer.be: Vidéos et actualités sur le Cameroun et la Diaspora.', $videos[0]['titre'] ?? '' );
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            '@id' => "{$url}#collection",
            'name' => 'Vidéos Cameroun, Info & Analyse | Camer.be', 'description' => $description,
            'url' => $url,
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => $items,
                ],
            ];
    }
    /** * Génère les éléments ItemList pour les vidéos. */
    private function getVideoItems(array $videos): array
    {
        $items = [];
        foreach ($videos as $index => $video) {
            $snippet = $video['youtubeApi']['snippet'] ?? [];
            $contentDetails = $video['youtubeApi']['contentDetails'] ?? [];
            $title = $video['titre'] ?? '';
            $slug = Str::slug($title);
            $url = rtrim(config('app.url'), '/') . "/videos/{$video['id']}/{$slug}";
            $description = Str::limit( $snippet['description'] ?? '', 155 );
            $keywords = isset($snippet['tags']) ? implode(', ', $snippet['tags']) : '';

            $thumbnails = $snippet['thumbnails'] ?? [];
            $bestThumb = $thumbnails['maxres'] ??
                $thumbnails['standard'] ??
                $thumbnails['high'] ??
                $thumbnails['medium'] ??
                $thumbnails['default'] ?? null;
            $thumbnailUrls = array_values( array_filter( array_map( fn ($thumbnail) => $thumbnail['url'] ?? null, $thumbnails ) ) );
            $youtubeWatchUrl = "https://www.youtube.com/watch?v=" . ($video['video'] ?? '');
            $videoObject = [
                '@type' => 'VideoObject',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $url,
                    ],
                'name' => $title,
                'description' => $description ?: $title,
                'thumbnailUrl' => $thumbnailUrls,
                'uploadDate' => $this->toIsoDate( $snippet['publishedAt'] ?? null ),
                'duration' => $contentDetails['duration'] ?? null,
                'contentUrl' => $youtubeWatchUrl,
                'embedUrl' => $video['video_url'] ?? null,
                'inLanguage' => $snippet['defaultLanguage'] ?? 'fr',
                'url' => $url,
                'keywords' =>  array_map('trim', explode(',', $keywords)),
                'isAccessibleForFree' => true,
                'copyrightYear' => $this->getYear( $snippet['publishedAt'] ?? null ),
                'publisher' => $this->publisher(),
                'sameAs' => [
                    'https://www.facebook.com/camergroup',
                    'https://x.com/camerbe',
                    ],
                ];
            /* * On ajoute thumbnail seulement si * YouTube fournit réellement une miniature. */
            if ($bestThumb) {
                $videoObject['thumbnail'] = [
                    '@type' => 'ImageObject',
                    'url' => $bestThumb['url'] ?? null,
                    'width' => $bestThumb['width'] ?? null,
                    'height' => $bestThumb['height'] ?? null,
                    'caption' => $title, ];
            } /* * Nettoyage des valeurs null. */
            $videoObject = $this->removeNullValues( $videoObject );
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => $videoObject, ];
        }
        return $items;
    } /** * Informations communes au publisher. */
    private function publisher(): array {
        return [
            '@type' => 'Organization',
            'name' => 'Camer.be',
            'url' => 'https://www.camer.be',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => config( 'schema.publisher_logo', 'https://www.camer.be/images/logo.png' ),
                'width' => 190, 'height' => 52,
                ],
            ];
    }
    /** * Image Schema.org d'un article. */
    private function articleImage( array $article, string $title ): array {
        $dimensions = [
            'width' => $article['image_width'] ?? 1200,
            'height' => $article['image_height'] ?? 675, ];
        return [
            '@type' => 'ImageObject',
            'url' => $article['image_url'] ?? 'https://picsum.photos/1200/675?random=2',
            'width' => (int) $dimensions['width'],
            'height' => (int) $dimensions['height'],
            'caption' => $title,
            ];
    }
    /** * Description dynamique d'une CollectionPage. */
    private function buildCollectionDescription( string $prefix, string $title ): string {
        $description = trim( $prefix . ' À la une : ' . $title );
        return mb_substr( $description, 0, 155, 'UTF-8' ) . '…';
    } /** * Conversion d'une date vers ISO 8601. */
    private function toIsoDate(?string $date): ?string {
        if (!$date) {
            return null;
        }
        try {
            return Carbon::parse($date)->toIso8601String();
        } catch (\Throwable) {
            return null;
        }
    }
    /** * Récupère l'année d'une date. */
    private function getYear(?string $date): ?int {
        if (!$date) {
            return null;
        }
        try {
            return Carbon::parse($date)->year;
        } catch (\Throwable) {
            return null;
        }
    } /** * Supprime récursivement les valeurs null. */
    private function removeNullValues(array $data): array {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->removeNullValues($value);
                if ($data[$key] === []) {
                    unset($data[$key]);
                }
                continue;
            }
            if ($value === null) {
                unset($data[$key]);
            }
        }
        return $data;
    }
    private function isAmp(): bool {
        return request()->is('amp') ||
            request()->is('amp/*') ||
            request()->routeIs('amp.*') ||
            str_starts_with( '/' . ltrim(request()->path(), '/'),
                '/amp'
            );
    }
    /** * Retourne l'URL canonique de l'application. */
    private function appUrl(): string {
        return rtrim(config('app.url'), '/');
    }
    /** * Construit l'URL canonique d'un article. * * Important : * Même depuis une page AMP, le Schema.org doit référencer * l'URL canonique de l'article et non son URL AMP. */
    private function articleUrl(array $article): string {
        $path = Helper::makeUrl(
            $article['rubrique']['rubrique'] ?? '',
                $article['sousrubrique']['sousrubrique'] ?? '',
                $article['slug'] ?? ''
        );
        return $this->appUrl() . '/' . ltrim($path, '/');
    }
    /** * Retourne l'URL canonique de la page courante. * * Sur une page AMP, on retire le préfixe /amp. */
    private function canonicalCurrentUrl(): string
    {
        $url = url()->current();
        // Si la requête est AMP, suppression du préfixe /amp
        if ($this->isAmp()) {
            $path = parse_url($url, PHP_URL_PATH) ?? '/';
            $path = preg_replace( '#^/amp(?:/|$)#', '/', $path );
            return $this->appUrl() . '/' . ltrim($path, '/');
        }
        return $url;
    }
}

