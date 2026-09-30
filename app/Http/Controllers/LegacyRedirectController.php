<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sousrubrique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegacyRedirectController extends Controller
{
    //
    public function article(int $id): RedirectResponse
    {
        $article = Article::with(['countries', 'rubrique', 'sousrubrique'])->findOrFail($id);

        $rubrique=Str::slug($article->rubrique->rubrique);
        $sousrubrique=$article->sousrubrique->slug;
        return redirect(
            '/' . $rubrique
            . '/' . $sousrubrique
            . '/' . $article->slug,
            301
        );
    }
    public function category(int $sub): RedirectResponse
    {
        $sousrubrique = Sousrubrique::with('rubrique')->findOrFail($sub);
        $rubrique=Str::slug($sousrubrique->rubrique->rubrique);
        return redirect(
            '/' . $rubrique. '/' . $sousrubrique->slug,
            301
        );
    }
}
