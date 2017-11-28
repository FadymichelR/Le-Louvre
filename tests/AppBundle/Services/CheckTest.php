<?php
namespace Tests\AppBundle\Services;
use AppBundle\Services\Check;
use PHPUnit\Framework\TestCase;

class CheckTest extends TestCase 
{
    public function testCheckVerif()
    {
        $visit = new Check();
        $date_visit = new \DateTime('2017-07-09');
        $result = $visit->dateVerif($date_visit);


        // assert that your calculator added the numbers correctly!
         $this->assertEquals(true, $result);
    }
}