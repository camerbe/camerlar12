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
    public static function getPubDimension($dimension){
        $data=[
            728=>1,
            300=>2
        ];
        return $data[$dimension] ?? null;
    }
    public static function getTitle($pays, $titre, $country){
        if ($pays === $country) {
            return stripos($titre, $pays) !== false ? $titre : "$pays :: $titre";
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
                $item = str_replace(' ', '', $item);
                $item = ucfirst($item);
            }

            return Str::camel($item);
        }, explode(',', $keywords)));
    }
}
