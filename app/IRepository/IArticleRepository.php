<?php

namespace App\IRepository;

interface IArticleRepository
{
    function getArticleByUser($user);
    function getScheduledArticle();
    function search($request);
    function getArticles();
    function getArticleBySlug($slug);
    function getTopNews(string $period);
    function getSameRubrique(int $fksousrubrique);
    function getMostReadRubriqueByCountry($fksousrubrique,$fkpays);
    function getMostReaded();
    function getNewsByAuthor($author);
    function getNewsForRss();
    function allCountries();
    function allRubrique();
    function getSportArticle();
    function getRubriqueArticles($fksousrubrique,$fkrubrique);
    function getOneRubriqueArticles($fksousrubrique,$fkrubrique);

}
