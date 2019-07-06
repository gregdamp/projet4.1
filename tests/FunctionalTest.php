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

        $crawler = $client->request('get', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
       
    }
}
