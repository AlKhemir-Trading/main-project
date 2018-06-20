<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Perte
 *
 * @ORM\Table(name="perte")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerteRepository")
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
     *
     * @var ElementArrivage
     *
     * @ORM\ManyToOne(targetEntity="ElementArrivage", inversedBy="perdus")
     * @ORM\JoinColumn(name="element_arrivage_id", referencedColumnName="id", nullable=FALSE)
     */
    private $elementArrivage;

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
}
