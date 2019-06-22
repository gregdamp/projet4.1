<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Billets;
use App\Repository\BilletsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends AbstractController

{
    /**
     * @Route("/site", name="site")
     */
    public function index() {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @route("/", name="home")
     */
    public function home(Request $request) {

        $date = null;

        if(isset($_POST['date'])) {
            $strtotime = strtotime($_POST['date']);
            $date = date('Y-m-d',$strtotime);
        }
        else {
            $date = '2019-01-13';
        }

        $TicketsDate = $this->getDoctrine()
            ->getRepository('App:Billets')
            ->TicketsByDate($date);
        
        $SoldOutDate = $this->getDoctrine()
            ->getRepository('App:Billets')
            ->SoldOutDate();

        return $this->render('site/home.html.twig', array(
            'TicketsDate'=>1000-$TicketsDate, 'SoldOutDate' => $SoldOutDate) );
        
    }

    /**
     * @route("/ajax", name="ajax")
     */
    public function ajax(Request $request) {

        $date = null;

        if(isset($_POST['date'])) {
            $strtotime = strtotime($_POST['date']);
            $date = date('Y-m-d',$strtotime);
        }
        else {
            $date = '2019-01-13';
        }

        $TicketsDate = $this->getDoctrine()
            ->getRepository('App:Billets')
            ->TicketsByDate($date);

        $SoldOutDate = $this->getDoctrine()
            ->getRepository('App:Billets')
            ->SoldOutDate();

        $response = new Response(
            '6',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;

    }

    /**
     * @route("/site/detail", name="ticket_detail")
     */
    public function detail() {
        return $this->render('site/detail.html.twig');
    }


}
