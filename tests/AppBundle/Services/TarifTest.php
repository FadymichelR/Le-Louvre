<?php
namespace Tests\AppBundle\Services;
use AppBundle\Services\Tarif;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class TarifTest extends TestCase 
{
    public function testPrice()
    {
        $tarif = new Tarif();
        $result = $tarif->price('2002-12-25', false);


        // assert that your calculator added the numbers correctly!
        $this->assertEquals(16, $result);
    }
}