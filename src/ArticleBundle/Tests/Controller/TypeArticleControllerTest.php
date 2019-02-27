<?php

namespace ArticleBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TypeArticleControllerTest extends WebTestCase
{
    public function testAjoutertypearticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajouterTypeArticle');
    }

    public function testModifiertypearticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifierTypeArticle');
    }

    public function testSupprimertypearticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/supprimerTypeArticle');
    }

    public function testListertypesarticles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listerTypesArticles');
    }

}
