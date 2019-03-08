<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Billets;

class UnitTest extends TestCase

{
    public function testBilletsQuantite() {
        $billets = new Billets();

        $billets->setQuantite(6);

        $this->assertEquals($billets->getQuantite(), 6);
    }

    public function testBilletsDate() {
        $billets = new Billets();

        $billets->setDate('26-03-2019');

        $this->assertEquals($billets->getDate(), '26-03-2019');
    }

}