<?php
namespace Tests\AppBundle\Services;
use AppBundle\Services\CheckTime;
use PHPUnit\Framework\TestCase;

class CheckTimeTest extends TestCase 
{
    public function testCheckToday()
    {
        $check = new CheckTime();
        $date_visit = new \DateTime('2017-07-03');
        $result = $check->checkToday(1,$date_visit);


        // assert that your calculator added the numbers correctly!
         $this->assertEquals(true, $result);
    }
}