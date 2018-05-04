<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Arrivage;

/**
 * ArrivageProduit
 *
 * @ORM\Table(name="arrivage_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArrivageProduitRepository")
 */
class ArrivageProduit
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
     * @ORM\ManyToOne(targetEntity="Arrivage", inversedBy="arrivageProduits")
     * @ORM\JoinColumn(name="arrivage_id", referencedColumnName="id", nullable=FALSE)
     */
     private $arrivage;

     /**
      * @var Produit
      *
      * @ORM\ManyToOne(targetEntity="Produit", inversedBy="arrivageProduits")
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
     * @return ArrivageProduit
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
     * @return ArrivageProduit
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
     * @return ArrivageProduit
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
     * @return ArrivageProduit
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
}
