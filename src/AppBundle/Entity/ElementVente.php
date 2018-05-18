<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementVente
 *
 * @ORM\Table(name="element_vente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ElementVenteRepository")
 */
class ElementVente
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
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="prixUnit", type="decimal", precision=10, scale=3)
     */
    private $prixUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=3)
     */
    private $montant;

    /**
     * @var Vente
     *
     * @ORM\ManyToOne(targetEntity="Vente", inversedBy="elementsVente")
     * @ORM\JoinColumn(name="vente_id", referencedColumnName="id", nullable=FALSE)
     */
    private $vente;

    /**
     *
     * @var ElementArrivage
     *
     * @ORM\ManyToOne(targetEntity="ElementArrivage", inversedBy="elementsVente")
     * @ORM\JoinColumn(name="element_arrivage_id", referencedColumnName="id", nullable=FALSE)
     */
    private $elementArrivage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quantite = 0;
        $this->prixUnit = 0;
        $this->montant = 0;
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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return ElementVente
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set elementArrivage
     *
     * @param \AppBundle\Entity\ElementArrivage $elementArrivage
     *
     * @return ElementVente
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
     * Set vente
     *
     * @param \AppBundle\Entity\Vente $vente
     *
     * @return ElementVente
     */
    public function setVente(\AppBundle\Entity\Vente $vente)
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

    /**
     * Set prixUnit
     *
     * @param string $prixUnit
     *
     * @return ElementVente
     */
    public function setPrixUnit($prixUnit)
    {
        $this->prixUnit = $prixUnit;

        return $this;
    }

    /**
     * Get prixUnit
     *
     * @return string
     */
    public function getPrixUnit()
    {
        return $this->prixUnit;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return ElementVente
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
}
