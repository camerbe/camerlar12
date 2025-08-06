<?php

namespace App\Helpers;

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
}
