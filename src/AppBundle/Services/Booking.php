<?php

namespace AppBundle\Services;

use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\EntityManager;

class Booking
{
  private $_em;
  private $_now;

  public function __construct(EntityManager $em) {
    $this->_em = $em;
    $this->_now = new \DateTime('today');
  }

  public function checkDate($date_visit)
  {
    if ($date_visit->format('N') == 7) {
      return true;
    }
    $annee = $date_visit->format('Y');
    /* On récupère la date de Pâques pour l'année sous forme de timestamp */
    $paques = easter_date($annee);

    $joursFeries = array(
        /* Dates fixes */
        new \DateTime($annee.'-01-01'), /* Jour de l'an */
        new \DateTime($annee.'-05-01'), /* Fête du travail */
        new \DateTime($annee.'-05-08'), /* Victoire des alliés */
        new \DateTime($annee.'-07-14'), /* Fête nationale */
        new \DateTime($annee.'-08-15'), /* Assomption */
        new \DateTime($annee.'-11-01'), /* Toussaint */
        new \DateTime($annee.'-11-11'), /* Armistice */
        new \DateTime($annee.'-12-25'), /* Noël */
        /* 3 jours fériés variables en fonction du jour de Pâques */
        new \DateTime($annee.'-'.date('n', $paques+(24*3600)).'-'.date('j', $paques+(24*3600))), /* Lundi de Pâques */
        new \DateTime($annee.'-'.date('n', $paques+(39*24*3600)).'-'.date('j', $paques+(39*24*3600))), /* Ascension */
        new \DateTime($annee.'-'.date('n', $paques+(50*24*3600)).'-'.date('j', $paques+(50*24*3600))) /* Lundi de Pentecôte */
    );

    /* On vérifie si le jour entré est un jour férié */
    foreach ($joursFeries as $jourFerie) {
        if($jourFerie->format('Y-m-d') == $date_visit->format('Y-m-d')){
            return true;
            break;
        }
    }

    return false;
  }

  public function date($type,$date_visit)
  {

    $hour = date('G');
    if ($this->_now == $date_visit && $type == 1 && $hour >= 14) {
        return true;
    }
    else {
        return false;
    }
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