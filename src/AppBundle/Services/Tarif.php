<?php

namespace AppBundle\Services;
use AppBundle\Entity\Reservation;
use Symfony\Component\Validator\Constraints\DateTime;

class Tarif
{

    private $_total_price = 0;


  public function price($birth,$reduced)
  {

	$from = new \DateTime($birth);
	$to   = new \DateTime('today');
	$age = $from->diff($to)->y;

   switch($age)
    {
    case ($age < 4):
        $price = 0;
    break;

    case ($age >= 4 AND $age < 12):
        $price = 8;
    break;

    case ($age >= 12 AND $age < 60):
        if ($reduced === true) { 
            $price = 10;
        }
        else {
            $price = 16;
        }
    break;

    case ($age > 60):
        if ($reduced === true) { 
            $price = 10;
        }
        else {
            $price = 12;
        }
    break;
    }
    return $price;

  }
  public function definePrice(Reservation $reservation) {

      $billetsTab = $reservation->getBillets();
      $this->_total_price = 0;
      foreach ($billetsTab as $billet) {
        $billet->setPrice($this->price($billet->getBirth()->format('Y-m-d'),$billet->getReducedPrice()));

        $this->_total_price += $billet->getPrice();

      }
      $reservation->setTotalPrice($this->getTotalPrice());
  }

  public function getTotalPrice() {

    return $this->_total_price;
    }
}