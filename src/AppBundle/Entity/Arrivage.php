<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Arrivage;

/**
 * Arrivage
 *
 * @ORM\Table(name="arrivage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArrivageRepository")
 */
class Arrivage
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var Arrivage
     * @ORM\OneToMany(targetEntity="ArrivageProduit", mappedBy="arrivage", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    private $arrivageProduits;

    public function __construct() {
      $this->dateCreation = new \DateTime();
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Arrivage
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Add arrivageProduit
     *
     * @param \AppBundle\Entity\ArrivageProduit $arrivageProduit
     *
     * @return Arrivage
     */
    public function addArrivageProduit(\AppBundle\Entity\ArrivageProduit $arrivageProduit)
    {
        $this->arrivageProduits[] = $arrivageProduit;

        return $this;
    }

    /**
     * Remove arrivageProduit
     *
     * @param \AppBundle\Entity\ArrivageProduit $arrivageProduit
     */
    public function removeArrivageProduit(\AppBundle\Entity\ArrivageProduit $arrivageProduit)
    {
        $this->arrivageProduits->removeElement($arrivageProduit);
    }

    /**
     * Get arrivageProduits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArrivageProduits()
    {
        return $this->arrivageProduits;
    }
}
