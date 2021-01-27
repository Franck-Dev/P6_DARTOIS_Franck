<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TricksControllerTests extends WebTestCase
{    
    /**
     * testricksNew Test de la fonction de création de Tricks
     * Renvoie une redirection temporaire
     *
     * @return void
     */
    public function testricksNew()
    {
        $client = static::createClient();

        $client->request('GET', '/tricks/new');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        // $this->assertResponseRedirects('/tricks/new.html.twig');
    }
    
    /**
     * testricksIndex Test fonction index des Tricks
     *
     * @return void
     */
    public function testricksIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/tricks');

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }
}
