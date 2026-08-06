<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DOMDocument;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
class Helper
{
    /**
     * @param $html
     * @return mixed|string|null

    public static function extractImgSrc($html){
        if(strpos($html, '<img') !== false){
            preg_match('/<img[^>]+src=["\'](.*?)["\']/', $html, $matches);
            return $matches[1] ?? null;
        }
        return $html;
    }*/

    public static function extractImgSrc(string $html): ?string
    {
        if (strpos($html, '<img') === false) {

            return 'https://picsum.photos/750/750';
        }

        preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $html, $matches);
        $src = $matches[1] ?? null;

        if (!$src) {
            return 'https://picsum.photos/750/750';
        }

        // Déjà une URL complète
        if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
            return $src;
        }

        // URL relative → reconstruction
        $base = 'https://www.camer.be';
        $src  = ltrim($src, '/');

        return "{$base}/{$src}";
    }
    public static function extractWidth($html){
        if(strpos($html, '<img') !== false){
            preg_match('/<img[^>]+width=["\'](.*?)["\']/', $html, $matches);
            return $matches[1] ?? null;
        }
        return $html;
    }
    public static function extractHeight($html){
        if(strpos($html, '<img') !== false){
            preg_match('/<img[^>]+height=["\'](.*?)["\']/', $html, $matches);
            return $matches[1] ?? null;
        }
        return $html;
    }
    public static function parseImageUrl(string $string): string
    {
        return str_starts_with($string, 'http')
            ? $string
            : 'https://www.camer.be' . $string;
    }
    public static function getPubDimension($dimension){
        $data=[
            728=>1,
            300=>2
        ];
        return $data[$dimension] ?? null;
    }
    public static function getTitle($pays, $titre, $country){
        $bled=Str::title($pays);

        return Str::contains(strtolower($titre), strtolower($pays))
            ? $titre
            : "$bled - $titre";
    }
    public static function getKeywords($keywords){
        return implode(',', array_map(function($item) {
            $item = trim($item);

            if (str_contains($item, '#')) {
                $item = ucfirst($item);

            }
            Str::camel($item);
        }, explode(',', $keywords)));
    }
    public static function guillemets(string $text):string {
        return preg_replace('/"([^"]+)"/u', "«\u{202F}$1\u{202F}»", $text);
    }
    public static function getYouTubeThumbnail($url){
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        if(preg_match($pattern, $url, $matches)){
            $videoId = $matches[1];
            return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
        }
        return null;
    }
    public static function getYouTubeId($url){
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        if(preg_match($pattern, $url, $matches)){
            return $matches[1];
        }
        return null;
    }

    public static function FindYoutube($string)
    {

        $attrs="";
        $dom = new \DOMDocument();
        $libxml_previous_state = libxml_use_internal_errors( true );
//        $html=  ;

        $dom->loadHTML(mb_convert_encoding( $string, 'HTML-ENTITIES', 'UTF-8'),LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

        $iframes = $dom->getElementsByTagName('iframe');
        foreach($iframes as $ifr)
        {
            $attrs = $ifr->getAttribute('src');
            break;
        }
        return $attrs;
    }

    public static function convertImgToAmpImg(string $html){
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML(
            mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        $images = $dom->getElementsByTagName('img');
        for ($i = $images->length - 1; $i >= 0; $i--) {

            $img = $images->item($i);

            // New amp-img
            $ampImg = $dom->createElement("amp-img");

            // Copy attributes
            foreach ($img->attributes as $attr) {
                $ampImg->setAttribute($attr->nodeName, $attr->nodeValue);
            }

            // AMP requirements
            if (!$ampImg->hasAttribute("layout")) {
                $ampImg->setAttribute("layout", "responsive");
            }

            if (!$ampImg->hasAttribute("width")) {
                $ampImg->setAttribute("width", "800");
            }

            if (!$ampImg->hasAttribute("height")) {
                $ampImg->setAttribute("height", "600");
            }

            // Replace original <img> with <amp-img>
            $img->parentNode->replaceChild($ampImg, $img);
        }
        return $dom->saveHTML();
    }

    public static function convertYoutubeToAmp(string $html)
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML(
            mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $iframes = $dom->getElementsByTagName('iframe');

        for ($i = $iframes->length - 1; $i >= 0; $i--) {

            $iframe = $iframes->item($i);

            // Extract src
            $src = $iframe->getAttribute("src");
            if (!$src) {
                continue;
            }

            // Detect YouTube link
            if (!preg_match('#(youtube\.com|youtu\.be)#i', $src)) {
                continue;
            }

            // Extract video ID
            $videoId = null;

            // Format: https://www.youtube.com/embed/VIDEOID
            if (preg_match('#youtube\.com/embed/([^?&]+)#', $src, $m)) {
                $videoId = $m[1];
            }

            // Format: https://www.youtube.com/watch?v=VIDEOID
            elseif (preg_match('#v=([^?&]+)#', $src, $m)) {
                $videoId = $m[1];
            }

            // Format: https://youtu.be/VIDEOID
            elseif (preg_match('#youtu\.be/([^?&]+)#', $src, $m)) {
                $videoId = $m[1];
            }

            if (!$videoId) {
                continue;
            }

            // Create <amp-youtube>
            $ampYoutube = $dom->createElement("amp-youtube");
            $ampYoutube->setAttribute("data-videoid", $videoId);
            $ampYoutube->setAttribute("layout", "responsive");

            // Provide default size if missing
            $ampYoutube->setAttribute("width", "480");
            $ampYoutube->setAttribute("height", "270");

            // Replace iframe
            $iframe->parentNode->replaceChild($ampYoutube, $iframe);
        }

        return $dom->saveHTML();
    }

    public static function remove_amp_from_url(string $url): string {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';
        // Remove only leading /amp or /amp/
        $path = preg_replace('#^/amp(/|$)#', '/', $path);

        // Normalize double slashes
        $path = preg_replace('#//+#', '/', $path);
        return ($parsed['scheme'] ?? 'http') . '://' .
            ($parsed['host'] ?? '') .
            (isset($parsed['port']) ? ':' . $parsed['port'] : '') .
            $path .
            (isset($parsed['query']) ? '?' . $parsed['query'] : '') .
            (isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '');
    }


    public static function views_format(int $number, bool $withLabel = true): array
    {
        // Format court (3,4 k)
        if ($number >= 1000000000) {
            $short = number_format($number / 1000000000, 1, ',', ' ') . ' Md';
        } elseif ($number >= 1000000) {
            $short = number_format($number / 1000000, 1, ',', ' ') . ' M';
        } elseif ($number >= 1000) {
            $short = number_format($number / 1000, 1, ',', ' ') . ' k';
        } else {
            $short = number_format($number, 0, ',', ' ');
        }

        // Format long SEO (3 389 vues)
        $full = number_format($number, 0, ',', ' ') . ' vue' . ($number > 1 ? 's' : '');

        return [
            'short' => $short,
            'full' => $full,
        ];
    }
    public static function makeUrl($rubrique,$sousrubrique,$slug):string{
        return Str::slug($rubrique)
            .'/'
            .Str::slug($sousrubrique)
            .'/'.$slug;
    }
    public static function formatShort(string|CarbonInterface $publishedAt): string
    {
        $date = $publishedAt instanceof CarbonInterface
            ? $publishedAt
            : Carbon::parse($publishedAt);

        return $date->format('d M Y H:i');
    }

    /**
     * Récupère le type MIME d'une image à partir de son URL.
     *
     * @param string $imageUrl
     * @return string|null
     */
    public static function getImageMimeType(string $imageUrl): ?string
    {
        if (empty($imageUrl) || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return null;
        }
        $cacheKey = 'image_mime_' . md5($imageUrl);
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($imageUrl) {
            // Fallback direct sur l'extension, pas de requête réseau du tout
            $extension = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION));

            $map = [
                'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png', 'gif' => 'image/gif',
                'webp' => 'image/webp', 'svg' => 'image/svg+xml',
                'bmp' => 'image/bmp', 'ico' => 'image/x-icon',
            ];

            if (isset($map[$extension])) {
                return $map[$extension];
            }
            // Seulement si l'extension est absente/ambiguë, on tente le réseau — avec timeout court
            try {
                $context = stream_context_create([
                    'http' => ['method' => 'HEAD', 'timeout' => 3],
                ]);
                $headers = get_headers($imageUrl, true, $context);
                if ($headers && isset($headers['Content-Type'])) {
                    $contentType = is_array($headers['Content-Type']) ? end($headers['Content-Type']) : $headers['Content-Type'];
                    return trim(explode(';', $contentType)[0]);
                }
            } catch (\Exception $e) {
                // silencieux
            }

            return null;
        });

        // On tente d'abord de lire les headers via une requête HEAD (rapide, pas de téléchargement complet)
        /*try {
            $headers = get_headers($imageUrl, true);

            if ($headers && isset($headers['Content-Type'])) {
                $contentType = is_array($headers['Content-Type'])
                    ? end($headers['Content-Type'])
                    : $headers['Content-Type'];

                // On nettoie au cas où il y aurait un charset ajouté (ex: "image/jpeg; charset=UTF-8")
                return trim(explode(';', $contentType)[0]);
            }
        } catch (\Exception $e) {
            // On continue vers le fallback
        }

        // Fallback : déduction depuis l'extension du fichier
        $extension = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION));

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'webp':
                return 'image/webp';
            case 'svg':
                return 'image/svg+xml';
            case 'bmp':
                return 'image/bmp';
            case 'ico':
                return 'image/x-icon';
            default:
                return null;
        }*/
    }

    public static function getImageDimensions(string $imageUrl): array
    {
        if (empty($imageUrl) || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return ['width' => null, 'height' => null];
        }

        $cacheKey = 'image_dimensions_' . md5($imageUrl);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($imageUrl) {
            try {
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 5,
                        'user_agent' => 'Mozilla/5.0 (compatible; CamerBeBot/1.0)',
                    ],
                ]);

                $size = @getimagesize($imageUrl, $info);

                if ($size === false) {
                    return ['width' => null, 'height' => null];
                }

                return ['width' => $size[0], 'height' => $size[1]];
            } catch (\Exception $e) {
                return ['width' => null, 'height' => null];
            }
        });
    }

    public static function nettoyerHashtags(array $arrkeyword, int $limite = 5): array
    {
        $hashtag = array_filter($arrkeyword, function ($valeur) {
            return Str::contains($valeur, '#');
        });

        $hashtag = array_map(function ($valeur) {
            return ltrim(trim($valeur), '#');
        }, $hashtag);

        // réindexe le tableau (array_filter garde les clés d'origine)
        $hashtag = array_values($hashtag);

        return array_slice($hashtag, 0, $limite);
    }
    public static function hashtagsToDisplay(array $arrkeyword, int $limite = 4): array
    {
        $hashtag = array_filter($arrkeyword, function ($valeur) {
            return Str::contains($valeur, '#');
        });

       // réindexe le tableau (array_filter garde les clés d'origine)
        $hashtag = array_values($hashtag);

        return array_slice($hashtag, 0, $limite);
    }


}
