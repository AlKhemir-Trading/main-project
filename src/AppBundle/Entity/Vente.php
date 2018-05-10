<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vente
 *
 * @ORM\Table(name="vente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VenteRepository")
 */
class Vente
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=3)
     */
    private $montant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ElementVente", mappedBy="vente", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    private $elementsVente;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elementsVente = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Vente
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
     * Set montant
     *
     * @param string $montant
     *
     * @return Vente
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
     * Add elementsVente
     *
     * @param \AppBundle\Entity\ElementVente $elementsVente
     *
     * @return Vente
     */
    public function addElementsVente(\AppBundle\Entity\ElementVente $elementsVente)
    {
        $this->elementsVente[] = $elementsVente;

        return $this;
    }

    /**
     * Remove elementsVente
     *
     * @param \AppBundle\Entity\ElementVente $elementsVente
     */
    public function removeElementsVente(\AppBundle\Entity\ElementVente $elementsVente)
    {
        $this->elementsVente->removeElement($elementsVente);
    }

    /**
     * Get elementsVente
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementsVente()
    {
        return $this->elementsVente;
    }
}
