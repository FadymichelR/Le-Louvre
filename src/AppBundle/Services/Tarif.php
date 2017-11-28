<?php

namespace AppBundle\Services;

use AppBundle\Entity\Billet;
use AppBundle\Entity\Reservation;
use Symfony\Component\Validator\Constraints\DateTime;

class Tarif
{

    private $_total_price = 0;
    const TARIF_NORMAL = 16;
    const TARIF_ENFANT = 8;
    const TARIF_SENIOR = 12;
    const TARIF_REDUIT = 10;
    const TARIF_BEBE = 0;


    public function price($birth, $reduced)
    {

        $from = new \DateTime($birth);
        $to = new \DateTime('today');
        $age = $from->diff($to)->y;

        switch (true) {
            case $age < 4:
                $price = self::TARIF_BEBE;
                break;

            case $age >= 4 AND $age < 12:
                $price = self::TARIF_ENFANT;
                break;

            case $age >= 12 AND $age < 60:
                if ($reduced === true) {
                    $price = self::TARIF_REDUIT;
                } else {
                    $price = self::TARIF_NORMAL;
                }
                break;

            case $age > 60:
                if ($reduced === true) {
                    $price = self::TARIF_REDUIT;
                } else {
                    $price = self::TARIF_SENIOR;
                }
                break;
        }
        return $price;

    }

    public function definePrice(Reservation $reservation)
    {
        /**
         * @var Reservation $billetsTab
         */
        $billetsTab = $reservation->getBillets();
        $this->_total_price = 0;
        foreach ($billetsTab as $billet) {
            /**
             * @var Billet $billet
             */
            $billet->setPrice($this->price($billet->getBirth()->format('Y-m-d'), $billet->getReducedPrice()));

            $this->_total_price += $billet->getPrice();

        }
        $reservation->setTotalPrice($this->getTotalPrice());
    }

    public function getTotalPrice()
    {

        return $this->_total_price;
    }
}
