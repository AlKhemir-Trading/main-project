<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Perte
 *
 * @ORM\Table(name="perte")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Perte
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
     * @var int
     *
     * @ORM\Column(name="perdu", type="smallint")
     */
    private $perdu;

    /**
     * @var string
     *
     * @ORM\Column(name="raison", type="string", length=100, nullable=true)
     */
    private $raison;

    /**
     * @var int
     *
     * @ORM\Column(name="date", type="datetime")
     */
     private $date;

    /**
     *
     * @var ElementArrivage
     *
     * @ORM\ManyToOne(targetEntity="ElementArrivage", inversedBy="perdus", cascade={"persist"} )
     * @ORM\JoinColumn(name="element_arrivage_id", referencedColumnName="id", nullable=FALSE)
     */
    private $elementArrivage;

    public function __construct(){

    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(){
      $this->date = new \DateTime();
      // die('aa'.$this->date->format('d/m/Y H:i'));
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
     * Set perdu
     *
     * @param integer $perdu
     *
     * @return Perte
     */
    public function setPerdu($perdu)
    {
        $this->perdu = $perdu;

        return $this;
    }

    /**
     * Get perdu
     *
     * @return int
     */
    public function getPerdu()
    {
        return $this->perdu;
    }

    /**
     * Set raison
     *
     * @param string $raison
     *
     * @return Perte
     */
    public function setRaison($raison)
    {
        $this->raison = $raison;

        return $this;
    }

    /**
     * Get raison
     *
     * @return string
     */
    public function getRaison()
    {
        return $this->raison;
    }

    /**
     * Set elementArrivage
     *
     * @param \AppBundle\Entity\ElementArrivage $elementArrivage
     *
     * @return Perte
     */
    public function setElementArrivage(\AppBundle\Entity\ElementArrivage $elementArrivage)
    {
        $this->elementArrivage = $elementArrivage;

        return $this;
    }

    /**
     * Get elementArrivage
     *
     * @return \AppBundle\Entity\ElementArrivage
     */
    public function getElementArrivage()
    {
        return $this->elementArrivage;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Perte
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
}
