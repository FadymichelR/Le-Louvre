<?php
namespace AppBundle\Services;

class Stripe
{

	public function __construct(){

		\Stripe\Stripe::setApiKey('sk_test_HruuLfakh7XU3JQQrawL3Bwd');
	}

	public function payment($token, $email, $price) {

		$price = $price * 100;

		try {
          \Stripe\Charge::create(array(
            "amount" => $price, // Amount in cents
            "currency" => "eur",
            "source" => $token,
            "description" => "Commande : $email ",
            "receipt_email" => $email
            ));

            return true;

            
        } catch(\Stripe\Error\Card $e) {
          // The card has been declined
            return false;
        }


	}


}
