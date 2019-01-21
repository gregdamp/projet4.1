<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Billets;
use App\Repository\BilletsRepository;
use Doctrine\ORM\EntityManager;

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
    public function home() {

      /*$entityManager = $this->getDoctrine()->getManager();

        $billet = new Billets();
        $billet->setQuantite(6);
        $billet->setDate('2018-12-21');


        $entityManager->persist($billet);

        $entityManager->flush(); */

        $date;

        if(isset($_POST['date'])) {
            $date = $_POST['date'];
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
            'TicketsDate'=>1000 - $TicketsDate, 'SoldOutDate'=> $SoldOutDate) );
        
        
    }

    /**
     * @route("/site/detail", name="ticket_detail")
     */
    public function detail() {
        return $this->render('site/detail.html.twig');
    }


}
