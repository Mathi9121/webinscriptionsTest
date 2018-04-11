<?php

namespace OCIM\ContactsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdresseControllerTest extends WebTestCase
{
    public function testAjouttypestructure()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'structure_ajout');
    }

}
