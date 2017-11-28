<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Services\Booking;
class AjaxController extends Controller
{

    /**
     * @Route("/ajax/availability/", name="availability")
     * @Method({"GET"})
     */
    public function availabilityAction(Request $request)
    {
      if ($request->isXmlHttpRequest()) {

          $date_visit = $request->query->get('date');
          $newDate = \DateTime::createFromFormat('d/m/Y', $date_visit)->format('Y-m-d');
          $new_date = new \DateTime($newDate);
          $booking = $this->get(Booking::class);

          return new JsonResponse(array('quantity' => $booking->numberOfTickets($new_date)));

      }
      else {
        throw new NotFoundHttpException('Ce n\'est pas une requette ajax');
      }
    }
}
