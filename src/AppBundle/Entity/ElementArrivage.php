<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Arrivage;
use AppBundle\Entity\ElementVente;

/**
 * ElementArrivage
 *
 * @ORM\Table(name="element_arrivage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ElementArrivageRepository")
 */
class ElementArrivage
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
     * @var Arrivage
     *
     * @ORM\ManyToOne(targetEntity="Arrivage", inversedBy="elementArrivages")
     * @ORM\JoinColumn(name="arrivage_id", referencedColumnName="id", nullable=FALSE)
     */
     private $arrivage;

     /**
      * @var Produit
      *
      * @ORM\ManyToOne(targetEntity="Produit", inversedBy="elementArrivages")
      * @ORM\JoinColumn(name="produit_id", referencedColumnName="id", nullable=FALSE)
      */
      private $produit;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite_vendu", type="integer")
     */
    private $quantiteVendu;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_achat", type="decimal", precision=10, scale=3)
     */
    private $prixAchat;

    /**
     * @var string
     *
     * @ORM\Column(name="prixVente", type="decimal", precision=10, scale=3)
     */
    private $prixVente;

    /**
     *
     * @var ElementVente
     *
     * @ORM\OneToMany(targetEntity="ElementVente", mappedBy="elementArrivage", cascade={}, orphanRemoval=FALSE)
     */
    private $elementsVente;

    public function __construct() {
      // $this->quantite = 0;
      // $this->prixAchat = 0;
      // $this->prixVente = 0;
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
     * @return ElementArrivage
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
     * Set prixAchat
     *
     * @param string $prixAchat
     *
     * @return ElementArrivage
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    /**
     * Get prixAchat
     *
     * @return string
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Set prixVente
     *
     * @param string $prixVente
     *
     * @return ElementArrivage
     */
    public function setPrixVente($prixVente)
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    /**
     * Get prixVente
     *
     * @return string
     */
    public function getPrixVente()
    {
        return $this->prixVente;
    }

    /**
     * Set arrivage
     *
     * @param \AppBundle\Entity\Arrivage $arrivage
     *
     * @return ElementArrivage
     */
    public function setArrivage(\AppBundle\Entity\Arrivage $arrivage)
    {
        $this->arrivage = $arrivage;

        return $this;
    }

    /**
     * Get arrivage
     *
     * @return \AppBundle\Entity\Arrivage
     */
    public function getArrivage()
    {
        return $this->arrivage;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return ElementArrivage
     */
    public function setProduit(\AppBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \AppBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set quantiteVendu
     *
     * @param integer $quantiteVendu
     *
     * @return ElementArrivage
     */
    public function setQuantiteVendu($quantiteVendu)
    {
        $this->quantiteVendu = $quantiteVendu;

        return $this;
    }

    /**
     * Get quantiteVendu
     *
     * @return integer
     */
    public function getQuantiteVendu()
    {
        return $this->quantiteVendu;
    }

    /**
     * Add elementsVente
     *
     * @param \AppBundle\Entity\ElementVente $elementsVente
     *
     * @return ElementArrivage
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
