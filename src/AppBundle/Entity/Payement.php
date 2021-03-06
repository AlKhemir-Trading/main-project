<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Payement
 *
 * @ORM\Table(name="payement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PayementRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Payement
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
     * @Assert\NotBlank(message=" Spécifiez le payement")
     *
     * @ORM\Column(name="montant", type="decimal")
     */
    private $montant;

    /**
     * @var int
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
     private $date;

     /**
      * @var string
      *
      * @ORM\Column(name="note", type="string", length=255, nullable=true)
      */
     private $note;

     /**
      * @var string
      *
      * @ORM\Column(name="type", type="string", length=255)
      */
     private $type;

     /**
      * @var string
      *
      * @ORM\Column(name="num_cheque", type="string", length=255, nullable=true)
      */
     private $numCheque;

     /**
      * @var string
      *
      * @ORM\Column(name="pocesseur", type="string", length=255, nullable=true)
      */
     private $pocesseur;

     /**
      * @var string
      *
      * @ORM\Column(name="banque", type="string", length=255, nullable=true)
      */
     private $banque;

     /**
      * @var int
      *
      * @ORM\Column(name="date_cheque", type="datetime", nullable=true)
      */
      private $dateCheque;

      /**
       * @var string
       * @Assert\Choice(choices={null, 0, 1}, message="Choose a valid cheque status.")
       * @ORM\Column(name="etat_cheque", type="boolean", nullable=true)
       */
      private $etatCheque;

     /**
      * @var Client
      *
      * @ORM\ManyToOne(targetEntity="Client", inversedBy="payements", cascade={"persist"})
      */
     private $client;

     /**
      * @var Client
      *
      * @ORM\ManyToOne(targetEntity="User")
      */
     private $user;

     /**
      * @var ActionCaisse
      *
      * @ORM\OneToOne(targetEntity="ActionCaisse", inversedBy="payement")
      */
     private $actionCaisse;

     public function __construct() {
       $this->dateCheque = new \DateTime();
     }

     /**
      * @ORM\PrePersist
      */
     public function prePersist(){
       $this->date = new \DateTime();
       if($this->type == 'cash'){
         $this->numCheque = null;
         $this->pocesseur = null;
         $this->banque = null;
         $this->dateCheque = null;
       }else{
         //$this->dateCheque = new \DateTime();
         $this->etatCheque = 0;
       }
     }

     /**
      * @ORM\PrePersist
      * @ORM\PreUpdate
      * @ORM\PreRemove
      */
     public function updateClientFieldPlusOuMoins()
     {
       $this->getClient()->updatePlusOuMoins();
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
     * Set montant
     *
     * @param string $montant
     *
     * @return Payement
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Payement
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
     * Set vente
     *
     * @param \AppBundle\Entity\Vente $vente
     *
     * @return Payement
     */
    public function setVente(\AppBundle\Entity\Vente $vente = null)
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
     * Set note
     *
     * @param string $note
     *
     * @return Payement
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
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Payement
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
     * Set type
     *
     * @param string $type
     *
     * @return Payement
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set numCheque
     *
     * @param string $numCheque
     *
     * @return Payement
     */
    public function setNumCheque($numCheque)
    {
        $this->numCheque = $numCheque;

        return $this;
    }

    /**
     * Get numCheque
     *
     * @return string
     */
    public function getNumCheque()
    {
        return $this->numCheque;
    }

    /**
     * Set pocesseur
     *
     * @param string $pocesseur
     *
     * @return Payement
     */
    public function setPocesseur($pocesseur)
    {
        $this->pocesseur = $pocesseur;

        return $this;
    }

    /**
     * Get pocesseur
     *
     * @return string
     */
    public function getPocesseur()
    {
        return $this->pocesseur;
    }

    /**
     * Set banque
     *
     * @param string $banque
     *
     * @return Payement
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return string
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     * Set dateCheque
     *
     * @param \DateTime $dateCheque
     *
     * @return Payement
     */
    public function setDateCheque($dateCheque)
    {
        $this->dateCheque = $dateCheque;

        return $this;
    }

    /**
     * Get dateCheque
     *
     * @return \DateTime
     */
    public function getDateCheque()
    {
        return $this->dateCheque;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Payement
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set actionCaisse
     *
     * @param \AppBundle\Entity\ActionCaisse $actionCaisse
     *
     * @return Payement
     */
    public function setActionCaisse(\AppBundle\Entity\ActionCaisse $actionCaisse = null)
    {
        $this->actionCaisse = $actionCaisse;

        return $this;
    }

    /**
     * Get actionCaisse
     *
     * @return \AppBundle\Entity\ActionCaisse
     */
    public function getActionCaisse()
    {
        return $this->actionCaisse;
    }

    /**
     * Set etatCheque
     *
     * @param boolean $etatCheque
     *
     * @return Payement
     */
    public function setEtatCheque($etatCheque)
    {
        $this->etatCheque = $etatCheque;

        return $this;
    }

    /**
     * Get etatCheque
     *
     * @return boolean
     */
    public function getEtatCheque()
    {
        return $this->etatCheque;
    }
}
