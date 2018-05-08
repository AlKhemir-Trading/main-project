<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Arrivage;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var Fournisseur
     *
     * @ORM\ManyToOne(targetEntity="Fournisseur", inversedBy="arrivages", cascade={"persist"})
     */
    private $fournisseur;

    /**
     * @var Arrivage
     * @ORM\OneToMany(targetEntity="ElementArrivage", mappedBy="arrivage", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    private $elementArrivages;

    /**
     * @var string
     * @ORM\Column(name="note", type="string", length=255, nullable=true)
     */
    private $note;

    public function __construct() {
      $this->dateCreation = new \DateTime();
      $this->elementArrivages = new ArrayCollection();
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
     * Add elementArrivage
     *
     * @param \AppBundle\Entity\ElementArrivage $elementArrivage
     *
     * @return Arrivage
     */
    public function addElementArrivage(\AppBundle\Entity\ElementArrivage $elementArrivage)
    {
        //$elementArrivage->setArrivage($this);
        $this->elementArrivages[] = $elementArrivage;

        return $this;
    }

    /**
     * Remove elementArrivage
     *
     * @param \AppBundle\Entity\ElementArrivage $elementArrivage
     */
    public function removeElementArrivage(\AppBundle\Entity\ElementArrivage $elementArrivage)
    {
        $this->elementArrivages->removeElement($elementArrivage);
    }

    /**
     * Get elementArrivages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementArrivages()
    {
        return $this->elementArrivages;
    }

    /**
     * Set fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return Arrivage
     */
    public function setFournisseur(\AppBundle\Entity\Fournisseur $fournisseur = null)
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return \AppBundle\Entity\Fournisseur
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Arrivage
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
