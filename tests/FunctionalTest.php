<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controller\SiteController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class FunctionalTest extends WebTestCase

{
   public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');
       var_dump($_ENV);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
       
    }

    public function testSite()
    {
        $client = static::createClient();

        $client->request('GET', '/site');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}
