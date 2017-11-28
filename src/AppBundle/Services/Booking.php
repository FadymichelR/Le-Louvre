<?php

namespace AppBundle\Services;

use AppBundle\Entity\Reservation;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Services\Check;

class Booking
{
    /**
     * @var EntityManagerInterface
     */
    private $_em;
    private $_check;
    private $_checkTime;

    public function __construct(EntityManagerInterface $em)
    {
        $this->_em = $em;
        $this->_check = new Check();
        $this->_checkTime = new CheckTime();

    }

    public function checkDate(\DateTime $date_visit)
    {
        return $this->_check->dateVerif($date_visit);
    }

    public function date($type, $date_visit)
    {

        return $this->_checkTime->checkToday($type, $date_visit);
    }

    public function numberOfTickets($day)
    {


        $repository = $this->_em->getRepository('AppBundle:Reservation');

        $reservations = $repository->findBy(array('dateVisit' => $day));
        $billets = 0;

        foreach ($reservations as $reservation) {
            /**
             * @var Reservation $reservation
             */

            foreach ($reservation->getBillets() as $billet) {

                $billets++;

            }
        }
        return $billets;

    }

    public function limitOfBooking($date_visit, $type = null)
    {

        if (isset($type) && !empty($type)) {
            if ($this->date($type, $date_visit) === true) {
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