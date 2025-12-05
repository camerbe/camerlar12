<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\api\V1\ArticleController;
use Symfony\Component\HttpFoundation\Response;

class AmpController extends Controller
{
    //
    protected $api;
    public function __construct(ArticleController $api){
        $this->api=$api;
    }
    public function index(Request $request){
        $data = $this->api->getArticles();
        $array = json_decode($data->getContent(), true);

        $articles = collect($array['data']); // convert array to collection

        // Step 2: Set pagination variables
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $currentItems = $articles->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // Step 3: Create paginator
        $paginated = new LengthAwarePaginator(
            $currentItems,
            $articles->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Step 4: Return paginated data
        return view('index', [
            'articles' => $paginated
        ]);
    }
    public function getArticleBySlu($slug){
        $data=$this->api->getArticleBySlug($slug);
        $array = json_decode($data->getContent(), true);
        dd($array['data']);
    }
}
