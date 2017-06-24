<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Services\Booking;
use Symfony\Component\Validator\Constraints\DateTime;
class AjaxController extends Controller
{

    /**
     * @Route("/ajax/availability/", name="availability")
     */
    public function availabilityAction(Request $request)
    {
      if ($request->isXmlHttpRequest()) {
        $date_visit = $request->query->get('date');
        $new_date = \DateTime::createFromFormat('d/m/Y', $date_visit)->format('Y-m-d');
        $new_date = new \DateTime($new_date);
        $booking = $this->get(Booking::class);

        echo json_encode(array('quantity' => $booking->numberOfTickets($new_date)));
      }
      throw new NotFoundHttpException('Page innéxistante');
    }
}
