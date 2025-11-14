<?php

namespace App\Helpers;

use Illuminate\Support\Str;

/**
 *
 */
class Helper
{
    /**
     * @param $html
     * @return mixed|string|null
     */
    public static function extractImgSrc($html){
        if(strpos($html, '<img') !== false){
            preg_match('/<img[^>]+src=["\'](.*?)["\']/', $html, $matches);
            return $matches[1] ?? null;
        }
        return $html;
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
            300=>24
        ];
        return $data[$dimension] ?? null;
    }
    public static function getTitle($pays, $titre, $country){
        if ($pays === $country) {
            return stripos($titre, $pays) !== false ? $titre :  "$pays :: $titre";
        }
        $hasPays = stripos($titre, $pays) !== false;
        $hasCountry = stripos($titre, $country) !== false;
        if ($hasPays) {
            return "$titre :: $country";
        }

        if ($hasCountry) {
            return "$pays :: $titre";
        }

        return "$pays :: $titre :: $country";
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
}
