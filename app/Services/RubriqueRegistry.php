<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RubriqueRegistry
{
    /**
     * Retourne tout le tableau slug => id
     */
    public static function all(): array
    {
        return Config::get('rubriques.map', []);
    }

    protected static function normalizeSlug(string $slug): ?string
    {
        $ignored = ['amp', 'accueil'];

        $parts = explode('/', $slug);

        $parts = array_filter($parts, function ($part) use ($ignored) {
            return !in_array(strtolower($part), $ignored);
        });

        $slug = implode('/', $parts);

        return $slug !== '' ? $slug : null;
    }

    /**
     * Lève un 404 Laravel propre (au lieu d'un 500) si la rubrique est vide/invalide.
     */
    public static function idFor(string $slug): int
    {
        $slug = self::normalizeSlug($slug);

        if ($slug === null || ! array_key_exists($slug, $map = self::all())) {
            throw new NotFoundHttpException("Rubrique inconnue : {$slug}");
        }

        return $map[$slug];
    }

    public static function has(string $slug): bool
    {
        $slug = self::normalizeSlug($slug);

        return $slug !== null && array_key_exists($slug, self::all());
    }
}
