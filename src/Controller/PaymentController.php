<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use App\Form\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/{id}", name="payment_new", methods={"GET","POST"}      )
     */
    public function new(Campaign $campaign, Request $request)
    {

        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);
        
        

         if ($form->isSubmitted() && $form->isValid()) {
                
            $payment->getParticipant()->setCampaign($campaign);
            $payment->getParticipant()->setIshidden(0);
            $payment->setIshidden(0);
            
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($payment->getParticipant());
             $entityManager->persist($payment);
             
             $entityManager->flush();
             dd($payment);
             //return $this->redirectToRoute('campaign_show', ['id' => $campaign->getId()]);

        }

        return $this->render('payment/new2.html.twig', [
            'campaign' => $campaign,
            'form'=> $form->createView(),
        ]);
    }

     /**
     * @Route("/payment/{id}/save", name="payment_save", methods={"POST"}      )
     */
    public function save(Request $request, Campaign $campaign): Response
    {
        $amount = $request->request->get("amount");
        $name = $request->request->get("name");
        $mail = $request->request->get("mail");

        $participant = new Participant();
        $participant->setName($name);
        $participant->setEmail($mail);
        $participant->setCampaign($campaign);
        $participant->setIshidden(0);


        $payment = new Payment();
        $payment->setAmount($amount);
        $payment->setParticipant($participant);
        $payment->setIshidden(0);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($participant);
        $entityManager->persist($payment);
        $entityManager->flush();
        dd($payment, $amount);

    }

    
}
