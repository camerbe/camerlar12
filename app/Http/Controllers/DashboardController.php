<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\V1\PubDimensionController;
use App\Http\Controllers\api\V1\StatsController;
use App\Services\ArticleService;
use App\Services\EvenementService;
use App\Services\PubDimensionService;
use App\Services\PubService;
use App\Services\PubTypeService;
use App\Services\RubriqueService;
use App\Services\SousrubriqueService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $stat;
    protected $pubdimensionService;
    protected $rubriqueService;
    protected $pubTypeService;
    protected $sousRubriqueService;
    protected $pubService;
    protected $evenementService;
    protected $articleService;
    //

    /**
     * @param $stat
     */
    public function __construct(
        StatsController $stat,
        PubDimensionService $pubdimensionService,
        RubriqueService $rubriqueService,
        PubTypeService $pubTypeService,
        SousrubriqueService $sousRubriqueService,
        PubService $pubService,
        EvenementService $evenementService,
        ArticleService $articleService,
    )
    {
        $this->stat = $stat;
        $this->pubdimensionService = $pubdimensionService;
        $this->rubriqueService = $rubriqueService;
        $this->pubTypeService = $pubTypeService;
        $this->sousRubriqueService = $sousRubriqueService;
        $this->pubService = $pubService;
        $this->evenementService = $evenementService;
        $this->articleService = $articleService;

        $array = json_decode($this->stat->index()->getContent(), true);
        /****************** View share  ************************************/
        view()->share([
            'stat'=>$array,

        ]);
    }


    public function index(){

        //dd($array);
        return view('admin.dashboard');
    }
    /**************************
    ******** PubDimension *****
    ***************************
     */
    public function create(){
        return view('admin.pubdimension.create');
    }
    public function pubdimensionindex(){
        $pubdimensions=$this->pubdimensionService->index();
        return view('admin.pubdimension.index',[
            'pubdimensions'=>$pubdimensions
        ]);
    }
    public function pubdimensionedit($id){

        return view('admin.pubdimension.edit',[
            'id'=>$id
        ]);
    }
    /***************************
     ******** Rubrique     *****
     ***************************
     */
    public function rubriquecreate(){
        return view('admin.rubrique.create');
    }
    public function rubriqueindex(){
        $rubriques=$this->rubriqueService->index();
        $perPage = 15;
        $page = request()->get('page', 1);

        $paginated = new LengthAwarePaginator(
            $rubriques->forPage($page, $perPage),
            $rubriques->count(),
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => request()->query()]
        );



        return view('admin.rubrique.index',[
            'rubriques'=>$paginated,
        ]);
    }
    public function rubriqueedit($id){

        return view('admin.rubrique.edit',[
            'id'=>$id
        ]);
    }
    /***************************
     ******** PubType      *****
     ***************************
     */
    public function pubtypecreate(){
        return view('admin.pubtype.create');
    }
    public function pubtypeindex(){
        $pubtypes=$this->pubTypeService->getAll();

        return view('admin.pubtype.index',[
            'pubtypes'=>$pubtypes
        ]);
    }
    public function pubtypeedit($id){

        return view('admin.pubtype.edit',[
            'id'=>$id
        ]);
    }
    /***************************
     ******** SousRubrique *****
     ***************************
     */
    public function sousrubriquecreate(){
        return view('admin.sousrubrique.create');
    }
    public function sousrubriqueindex(){
        $sousrubriques=$this->sousRubriqueService->index();

        return view('admin.sousrubrique.index',[
            'sousrubriques'=>$sousrubriques
        ]);
    }
    public function sousrubriqueedit($id){

        return view('admin.sousrubrique.edit',[
            'id'=>$id
        ]);
    }
    /***************************
     ******** Vidéos       *****
     ***************************
     */
    public function videocreate(){
        return view('admin.video.create');
    }
    public function videoindex(){
        $sousrubriques=$this->sousRubriqueService->index();

        return view('admin.video.index',[
            'sousrubriques'=>$sousrubriques
        ]);
    }
    public function videoedit($id){

        return view('admin.video.edit',[
            'id'=>$id
        ]);
    }
    /***************************
     ******** Pub          *****
     ***************************
     */
    public function pubcreate(){
        return view('admin.pub.create');
    }
    public function pubindex(){
        $pubs=$this->pubService->index();

        return view('admin.pub.index',[
            'pubs'=>$pubs,
        ]);
    }
    public function pubedit($id){

        return view('admin.pub.edit',[
            'id'=>$id
        ]);
    }
    /***************************
     ******** Event        *****
     ***************************
     */
    public function eventcreate(){
        return view('admin.event.create');
    }
    public function eventindex(){
        $events=$this->pubService->index();

        return view('admin.event.index',[
            'events'=>$events,
        ]);
    }
    public function eventedit($id){

        return view('admin.event.edit',[
            'id'=>$id
        ]);
    }
    /***************************
     ******** Article      *****
     ***************************
     */
    public function articlecreate(){
        return view('admin.article.create');
    }
    public function articleindex($userid){
        $articles=$this->articleService->getArticleByUser($userid);

        return view('admin.article.index',[
            'articles'=>$articles,
        ]);
    }
    public function articleedit($id){

        return view('admin.article.edit',[
            'id'=>$id
        ]);
    }
    public function articlesearch($request){
        $articles=$this->articleService->search($request);
        //dd($articles);
        return view('admin.article.search',[
            'articles'=>$articles
        ]);
    }
}
