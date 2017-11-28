<?php

namespace AppBundle\Services;


class CheckTime
{

    private $_now;

    public function checkToday($type, $date_visit)
    {
        $this->_now = new \DateTime('today');
        $hour = date('G');
        if ($this->_now == $date_visit && $type == 1 && $hour >= 14) {
            return true;
        } else {
            return false;
        }
    }
}
