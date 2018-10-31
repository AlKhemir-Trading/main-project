<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\ElementCaisse;

/**
 * ActionCaisse
 *
 * @ORM\Table(name="action_caisse")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActionCaisseRepository")
 */
class ActionCaisse
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
     * @Assert\GreaterThan(0)
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=3)
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="string", length=255)
     */
    private $motif;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * @var Payement
     *
     * @ORM\OneToOne(targetEntity="Payement", mappedBy="actionCaisse")
     */
    private $payement;

    /**
     * @var ElementCaisse
     *
     * @ORM\ManyToOne(targetEntity="ElementCaisse", inversedBy="actionsCaisse", cascade={"persist"})
     */
    private $elementCaisse;

    public function __construct() {
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
     * Set montant
     *
     * @param string $montant
     *
     * @return ActionCaisse
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
     * @return ActionCaisse
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
     * Set motif
     *
     * @param string $motif
     *
     * @return ActionCaisse
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return ActionCaisse
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return ActionCaisse
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
     * Set payement
     *
     * @param \AppBundle\Entity\Payement $payement
     *
     * @return ActionCaisse
     */
    public function setPayement(\AppBundle\Entity\Payement $payement = null)
    {
        $this->payement = $payement;

        return $this;
    }

    /**
     * Get payement
     *
     * @return \AppBundle\Entity\Payement
     */
    public function getPayement()
    {
        return $this->payement;
    }

    /**
     * Set elementCaisse
     *
     * @param \AppBundle\Entity\ElementCaisse $elementCaisse
     *
     * @return ActionCaisse
     */
    public function setElementCaisse(\AppBundle\Entity\ElementCaisse $elementCaisse = null)
    {
        $this->elementCaisse = $elementCaisse;

        return $this;
    }

    /**
     * Get elementCaisse
     *
     * @return \AppBundle\Entity\ElementCaisse
     */
    public function getElementCaisse()
    {
        return $this->elementCaisse;
    }
}
