<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 */
class Produit
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @var int
     *
     * @ORM\Column(name="date_creation", type="date", nullable=false)
     */
     private $dateCreation;

     /**
      * @var Arrivage
      * @ORM\OneToMany(targetEntity="ElementArrivage", mappedBy="produit", cascade={"persist", "remove"}, orphanRemoval=TRUE)
      */
     private $elementArrivages;

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
     * Set name
     *
     * @param string $name
     *
     * @return Produit
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Produit
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

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Produit
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
     * @return Produit
     */
    public function addElementArrivage(\AppBundle\Entity\ElementArrivage $elementArrivage)
    {
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
}
