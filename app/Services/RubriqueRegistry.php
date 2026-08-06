<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class RubriqueRegistry
{
    /**
     * Retourne tout le tableau slug => id
     */
    public static function all(): array
    {
        return Config::get('rubriques.map', []);
    }

    /**
     * Retourne l'id d'une rubrique/sous-rubrique à partir de son slug.
     * Lance une exception explicite si le slug est inconnu (au lieu
     * d'une erreur "Undefined array key" muette).
     */
    public static function idFor(string $slug): int
    {
        $map = self::all();

        if (! array_key_exists($slug, $map)) {
            throw new \InvalidArgumentException("Rubrique inconnue : {$slug}");
        }

        return $map[$slug];
    }
    /**
     * Vérifie l'existence d'un slug sans lever d'exception.
     */
    public static function has(string $slug): bool
    {
        return array_key_exists($slug, self::all());
    }
}
