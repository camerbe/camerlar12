<?php

namespace App\Services;

use App\IRepository\IArticleRepository;
use PhpParser\Lexer\TokenEmulator\ReadonlyTokenEmulator;

class ArticleService
{

    public function __construct(protected IArticleRepository $articleRepository)
    {
    }

    function create(array $input){
       return $this->articleRepository->create($input);
    }
    function delete($id){
        return $this->articleRepository->delete($id);
    }
    function findById($id){
        return $this->articleRepository->findById($id);
    }
    function update(array $input, $id){
        return $this->articleRepository->update($input, $id);
    }
    function index(){
        return $this->articleRepository->index();
    }

    function getArticleByUser($user)
    {
        return $this->articleRepository->getArticleByUser($user);
    }
    function getScheduledArticle()
    {
        return $this->articleRepository->getScheduledArticle();
    }
    function search($request)
    {
        return $this->articleRepository->search($request);
    }
    function getArticleBySlug($slug){
        return $this->articleRepository->getArticleBySlug($slug);
    }
    function getArticles(){
        return $this->articleRepository->getArticles();
    }
    function getTopNews(string $period){
        return $this->articleRepository->getTopNews($period);
    }
    function getSameRubrique(int $fksousrubrique){
        return $this->articleRepository->getSameRubrique($fksousrubrique);
    }
    function getMostReadRubriqueByCountry($fksousrubrique, $fkpays){
        return $this->articleRepository->getMostReadRubriqueByCountry($fksousrubrique, $fkpays);
    }
    function getMostReaded(){
        return $this->articleRepository->getMostReaded();

    }
    function getNewsByAuthor($author){
        return $this->articleRepository->getNewsByAuthor($author);
    }
    function getNewsForRss(){
        return $this->articleRepository->getNewsForRss();
    }
    function allCountries(){
        return $this->articleRepository->allCountries();
    }
    function allRubrique(){
        return $this->articleRepository->allRubrique();
    }
    function getSportArticle(){
        return $this->articleRepository->getSportArticle();
    }
    function getRubriqueArticles($fksousrubrique, $fkrubrique){
        //dd($fkrubrique);
        return $this->articleRepository->getRubriqueArticles($fksousrubrique, $fkrubrique);
    }
}
