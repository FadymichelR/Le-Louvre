<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=50, unique=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas une adresse email valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var \Date
     *
     * @ORM\Column(name="date_visit", type="date")
     * @Assert\GreaterThanOrEqual("today", message="Il n’est pas possible de réserver pour les jours passés")
     * @Assert\NotBlank(message="Veuillez remplir ce champ s'il vous plaît")
     */
    private $dateVisit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reservation", type="datetime")
     */
    private $dateReservation;

    /**
    * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Billet", cascade={"persist"})
    * @Assert\NotBlank(message="Veuillez selectionner une personne")
    */
    private $billets;

    /**
     * @var float
     *
     * @ORM\Column(name="totalPrice", type="float")
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     * @Assert\Choice({"1", "0.5"})
     */
    private $type;

    public function __construct()
    {
        $this->dateReservation = new \Datetime();
        $this->reference = 'R'. mt_rand(1000000,9999999);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Reservation
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set dateVisit
     *
     * @param \DateTime $dateVisit
     *
     * @return Reservation
     */
    public function setDateVisit($dateVisit)
    {
        $this->dateVisit = $dateVisit;

        return $this;
    }

    /**
     * Get dateVisit
     *
     * @return \DateTime
     */
    public function getDateVisit()
    {
        return $this->dateVisit;
    }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     *
     * @return Reservation
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Reservation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set billets
     *
     * @param integer $billets
     *
     * @return Reservation
     */
    public function setBillets($billets)
    {
        $this->billets = $billets;

        return $this;
    }

    /**
     * Get billets
     *
     * @return integer
     */
    public function getBillets()
    {
        return $this->billets;
    }

    /**
     * Add billet
     *
     * @param \AppBundle\Entity\Billet $billet
     *
     * @return Reservation
     */
    public function addBillet(\AppBundle\Entity\Billet $billet)
    {
        $this->billets[] = $billet;

        return $this;
    }

    /**
     * Remove billet
     *
     * @param \AppBundle\Entity\Billet $billet
     */
    public function removeBillet(\AppBundle\Entity\Billet $billet)
    {
        $this->billets->removeElement($billet);
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     *
     * @return Reservation
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = number_format($totalPrice,2);

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
