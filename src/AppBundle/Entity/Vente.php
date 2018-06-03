<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as Assert2;
/**
 * Vente
 *
 * @ORM\Table(name="vente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VenteRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert2\ElementVente()
     *
     * @ORM\OneToMany(targetEntity="ElementVente", mappedBy="vente", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    private $elementsVente;


    /**
     * @var Client
     * @Assert\NotNull(message=" Vous devez specifier un Client. Veuillez aller à l'onglet Client et créer au moins un Client!")
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="ventes", cascade={"persist"})
     */
    private $client;


    // /**
    //  * @var Collection
    //  *
    //  * @ORM\OneToMany(targetEntity="Payement", mappedBy="vente", cascade={"persist", "remove"}, orphanRemoval=TRUE)
    //  */
    // private $payements;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_paye", type="decimal", precision=10, scale=3)
     */
    private $montantPaye;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elementsVente = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->payements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
    }


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistOrUpdate()
    {
        $montant = 0;
        foreach ($this->elementsVente as $element)
          $montant += $element->getMontant();
        $this->montant = $montant;
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

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Vente
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set montantPaye
     *
     * @param string $montantPaye
     *
     * @return Vente
     */
    public function setMontantPaye($montantPaye)
    {
        $this->montantPaye = $montantPaye;

        return $this;
    }

    /**
     * Get montantPaye
     *
     * @return string
     */
    public function getMontantPaye()
    {
        return $this->montantPaye;
    }

    /**
     * Add payement
     *
     * @param \AppBundle\Entity\Payement $payement
     *
     * @return Vente
     */
    public function addPayement(\AppBundle\Entity\Payement $payement)
    {
        $this->payements[] = $payement;

        return $this;
    }

    /**
     * Remove payement
     *
     * @param \AppBundle\Entity\Payement $payement
     */
    public function removePayement(\AppBundle\Entity\Payement $payement)
    {
        $this->payements->removeElement($payement);
    }

    /**
     * Get payements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayements()
    {
        return $this->payements;
    }
}
