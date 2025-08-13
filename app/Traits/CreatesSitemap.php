<?php

namespace App\Traits;

use Spatie\Sitemap\Sitemap;

trait CreatesSitemap
{
    protected function createSitemap(): Sitemap
    {
        return new Sitemap();
    }
}
