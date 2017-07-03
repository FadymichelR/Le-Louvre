<?php

namespace AppBundle\Services;

use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\EntityManager;
use AppBundle\Services\Check;

class Booking
{
  private $_em;

  public function __construct(EntityManager $em) {
    $this->_em = $em;

  }

  public function checkDate(\DateTime $date_visit)
  {
      $check = new Check();
      return $check->dateVerif($date_visit);
  }

  public function date($type,$date_visit)
  {

    $checkTime = new CheckTime();
    return $checkTime->checkToday($type,$date_visit);
  }
  public function numberOfTickets($day) {


    $repository = $this->_em->getRepository('AppBundle:Reservation');

    $reservations = $repository->findBy(array('dateVisit' => $day));
    $billets = 0;

    foreach ($reservations as $reservation) {

      foreach ($reservation->getBillets() as $billet) {
        $billets++;
        
      }
      
    }
    return $billets;

  }
  public function limitOfBooking($date_visit, $type = null) {

      if (isset($type) && !empty($type)) {
          if ($this->date($type,$date_visit) === true) {
            return array('name' => 'type', 'msg' => 'Le billet "journée" n\'est plus disponible après 14h.');
          }
      }
      if ($this->numberOfTickets($date_visit) >= 1000) {
        return array('name' => 'dateVisit', 'msg' => 'Impossible de réserver à cette date là, plus de 1000 billets ont déja été vendus.');
      }
      if ($this->checkDate($date_visit) === true) {

        return array('name' => 'dateVisit', 'msg' => 'il n’est pas possible de réserver les dimanches et les jours fériés.');
      }
      return false;

  }
}