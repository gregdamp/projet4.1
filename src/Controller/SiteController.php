<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Billets;
use App\Repository\BilletsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        //print_r($_ENV);die();

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
            1000 - $TicketsDate,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;

    }

    /**
     * @route("/site/detail", name="ticket_detail")
     */
    public function detail(SessionInterface $session, Request $request) {
        $mail = $request->request->get('email', '');
        $calendar = $request->request->get('calendar', '');
        $code = $request->request->get('code', '');
        $session->set('mail', $mail);
        $session->set('calendar', $calendar);
        $session->set('code', $code);

        //number tickers session store

        $normal = $request->request->get('normal');
        $enfant = $request->request->get('enfant');
        $senior = $request->request->get('senior');
        $session->set('normal', $normal);
        $session->set('enfant', $enfant);
        $session->set('senior', $senior);


        return $this->render('site/detail.html.twig');
    }

    /**
     * @route("/site/emails", name="emails")
     */
    public function emails(\Swift_Mailer $mailer, SessionInterface $session, Request $request) {
        

        \Stripe\Stripe::setApiKey("sk_test_ZxPQOAr7cF4W97P4qMjvyMrO");
        

        // Get the credit card details submitted by the form
        
        $token = $request->request->get('stripeToken');
        $amount = $request->request->get('hiddenPrice') * 100;
        echo($amount);
        // Create a charge: this will charge the user's card
            
            $charge = \Stripe\Charge::create(array(
                "amount" => abs($amount), // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));
        

        $message = (new \Swift_Message('Billeterie en ligne du Louvre'))
        ->setFrom('gregoire.damperont.udni@gmail.com')
        ->setTo($session->get('mail'))
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'site/emails.html.twig'
            ),
            'text/html'
        );

        $mailer->send($message); 
        
        $session->get('code');
        
        $ticketToStore = $session->get('normal') + $session->get('enfant') + $session->get('senior');

        $oldDate = $session->get('calendar');
        $newDate = date('Y-m-d', strtotime($oldDate));
        
        $entityManager = $this->getDoctrine()->getManager();

        $billet = new Billets();
        $billet->setQuantite($ticketToStore);
        $billet->setDate($newDate);


        $entityManager->persist($billet);

        $entityManager->flush();

        return $this->render('site/confirmation.html.twig');
    }

    /**
     * @route("/site/payment", name="payment")
     */
    public function payment(SessionInterface $session, Request $request) {
        $price = $request->request->get('hiddenPrice', '');
        $session->set('price', $price);
    
        var_dump($request);
    
        $i = 1;
        while ($i <= 6) {
                $normalName = $request->request->get('nomNormal'.$i, '');
                if ($normalName != '') {
                    $session->set('normalName'.$i, $normalName);
                }
                $i++;
        }
        $i = 1;
        while ($i <= 6) {
            $childName = $request->request->get('nomEnfant'.$i, '');
            if ($childName != '') {
                $session->set('childName'.$i, $childName);
            }   
            $i++;
        }
        $i = 1;
        while ($i <= 6) {
            $seniorName = $request->request->get('nomSenior'.$i, '');
            if ($seniorName != '') {
                $session->set('seniorName'.$i, $seniorName);
            }   
            $i++;
        }
    
        return $this->render('site/payment.html.twig');
    }

     /**
     * @route("/site/confirmation", name="confirmation")
     */
    public function confirmation(SessionInterface $session, Request $request) {

        return $this->render('site/confirmation.html.twig');
    }

}
