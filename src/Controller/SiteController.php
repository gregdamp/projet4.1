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

      /*$entityManager = $this->getDoctrine()->getManager();

        $billet = new Billets();
        $billet->setQuantite(6);
        $billet->setDate('2018-12-21');


        $entityManager->persist($billet);

        $entityManager->flush(); */

        $date = $request->request->get('date');
        var_dump($request->request->get('name'));    

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
    public function detail(SessionInterface $session, Request $request) {
        $mail = $request->request->get('email', '');
        $calendar = $request->request->get('calendar', '');
        $session->set('mail', $mail);
        $session->set('calendar', $calendar);

        return $this->render('site/detail.html.twig');
    }

    /**
     * @route("/site/emails", name="emails")
     */
    public function emails(\Swift_Mailer $mailer, SessionInterface $session, Request $request) {

        \Stripe\Stripe::setApiKey("sk_test_ZxPQOAr7cF4W97P4qMjvyMrO");

        // Get the credit card details submitted by the form
        var_dump($request->request->get('stripeToken', ''));
        $token = $request->request->get('stripeToken', '');


        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 1000, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));
            $this->addFlash("success","Bravo ça marche !");
            return $this->redirectToRoute("emails");
        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Snif ça marche pas :(");
            return $this->redirectToRoute("payment");
            // The card has been declined
        }

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

        return $this->render('site/confirmation.html.twig');
    }

    /**
     * @route("/site/payment", name="payment")
     */
    public function payment(SessionInterface $session, Request $request) {
        $price = $request->request->get('hiddenPrice', '');
        $session->set('price', $price);
    
    
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
    public function confirmation() {
        return $this->render('site/confirmation.html.twig');
    }


}
