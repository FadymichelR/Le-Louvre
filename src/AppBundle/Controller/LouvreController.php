<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reservation;
use AppBundle\Entity\Billet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\ReservationType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Services\Tarif;
use AppBundle\Services\Booking;
use AppBundle\Services\Mail;
use AppBundle\Services\Stripe;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class LouvreController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {

    $reservation = new Reservation();
    $form   = $this->get('form.factory')->create(ReservationType::class, $reservation);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

          $data = $form->getData();
          $booking = $this->get(Booking::class);

          $etat = $booking->limitOfBooking($reservation->getDateVisit(),$reservation->getType());
          if ($etat != false) {
            
            $form->get($etat['name'])->addError(new FormError($etat['msg']));
            return $this->render('default/index.html.twig', array('form' => $form->createView()));
          }

          $tarif = $this->get(Tarif::class);
          $tarif->definePrice($reservation);

          $session = $request->getSession();
          $session->set($reservation->getReference(), $reservation);
          return $this->redirectToRoute('booking', array('number' => $reservation->getReference()));

        }
        return $this->render('default/index.html.twig', array('form' => $form->createView()));
    }
    /**
     * @Route("/reservation/{number}", name="booking")
     */
    public function bookingAction($number, Request $request)
    {
        $session = $request->getSession();
        if ($session->has($number)) {
            $booking = $session->get($number);

            $defaultData = array('message' => 'Paiement en ligne');
            $form = $this->createFormBuilder($defaultData)
            ->setAction($this->generateUrl('booking', array('number' => $booking->getReference())))
            ->setMethod('POST')
            ->add('number', TextType::class, array(

              'attr' => array(
                  'placeholder' => 'Numéro de Carte',
                  'type' => 'tel',
                  'data-stripe' => 'number',
              ))
            )
            ->add('expirationmm', TextType::class, array(
              'attr' => array(
                  'placeholder' => 'MM',
                  'data-stripe' => 'exp_month',
                  'maxlength' => '2',
              )))
            ->add('expirationyy', TextType::class, array(
              'attr' => array(
                  'placeholder' => 'AA',
                  'data-stripe' => 'exp_year',
                  'maxlength' => '2',
              )))
            ->add('cvc', TextType::class, array(
              'attr' => array(
                  'placeholder' => 'CVC',
                  'data-stripe' => 'cvc',
                  'maxlength' => '4',
              )))
            ->add('send', SubmitType::class,  array( 'label' => 'Valider le paiement',))
            ->getForm();

              $form->handleRequest($request);
              if ($form->isSubmitted() && $form->isValid() && $request->request->get('stripeToken') != null) {
                  $session->set('stripeToken', $request->request->get('stripeToken'));
                  return $this->redirectToRoute('paiement', array('number' => $booking->getReference()));
              }

            return $this->render('default/booking.html.twig', array('data' => $booking, 'form' => $form->createView()));
        }
        else {
            throw new NotFoundHttpException('Page innéxistante');

        }
    }
    /**
    * @Route("/paiement/{number}", name="paiement")
    */
    public function paiementAction($number, Request $request)
    {
      $session = $request->getSession();
      if ($session->has($number) && $session->has('stripeToken')) {
            $booking = $session->get($number);
            $token = $session->get('stripeToken');

            $stripe = $this->get(Stripe::class);
            $etatPayment = $stripe->Payment($token,$booking->getEmail(),$booking->getTotalPrice());

            if ($etatPayment == true) {

              $em = $this->getDoctrine()->getManager();
              $em->persist($booking);
              $em->flush();
              $session->remove($number);

              $mail = $this->get(Mail::class);
              $mail->send(
                $booking->getEmail(),
                'Réservation au musée du louvre confirmée',
                $this->renderView('mail/reservation.html.twig',array('booking' => $booking))
                );

            }
            return $this->render('default/paiement.html.twig',array('data' => $booking, 'etat' => $etatPayment));

      }

      throw new NotFoundHttpException('Page innéxistante');
    }
}
