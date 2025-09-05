<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $totalAdmins=User::count();
        $publishedToday =Article::where('dateref',today())->count();
        $scheduled=Article::where('dateparution','>',now())->count();

        return response()->json([
            'total_admins'=>$totalAdmins,
            'published_today'=>$publishedToday,
            'scheduled'=>$scheduled
        ],Response::HTTP_OK);

    }


}
