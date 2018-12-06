<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/site", name="site")
     */
    public function index()
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @route("/", name="home")
     */
    public function home() {
        return $this->render('site/home.html.twig');
    }

    /**
     * @route("/site/detail", name="ticket_detail")
     */
    public function detail() {
        return $this->render('site/detail.html.twig');
    }
}
