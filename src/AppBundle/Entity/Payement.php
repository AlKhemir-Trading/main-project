<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Payement
 *
 * @ORM\Table(name="payement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PayementRepository")
 */
class Payement
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
     * @Assert\NotBlank(message=" Spécifiez le payement")
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $montant;

    /**
     * @var int
     *
     * @ORM\Column(name="date_creation", type="date", nullable=false)
     */
     private $date;

     /**
      * @var Vente
      *
      * @ORM\ManyToOne(targetEntity="Vente", inversedBy="payements")
      */
     private $vente;

     public function __construct() {
       $this->date = new \DateTime();
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
     * Set montant
     *
     * @param string $montant
     *
     * @return Payement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Payement
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set vente
     *
     * @param \AppBundle\Entity\Vente $vente
     *
     * @return Payement
     */
    public function setVente(\AppBundle\Entity\Vente $vente = null)
    {
        $this->vente = $vente;

        return $this;
    }

    /**
     * Get vente
     *
     * @return \AppBundle\Entity\Vente
     */
    public function getVente()
    {
        return $this->vente;
    }
}
