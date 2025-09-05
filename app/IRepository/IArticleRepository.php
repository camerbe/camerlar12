<?php

namespace App\IRepository;

interface IArticleRepository
{
    function getArticleByUser($user);
    function getScheduledArticle();
    function search($request);
    function getArticles($cmr='CM');
    function getArticleBySlug($slug);
    function getTopNews(string $period);
    function getSameRubrique(int $fksousrubrique);
    function getMostReadRubriqueByCountry($fksousrubrique,$fkpays);
    function getMostReaded();
    function getNewsByAuthor($author);
    function getNewsForRss();
    function allCountries();
    function allRubrique();

}
