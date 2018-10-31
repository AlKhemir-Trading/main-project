<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementCaisse
 *
 * @ORM\Table(name="element_caisse")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ElementCaisseRepository")
 */
class ElementCaisse
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
     * @ORM\Column(name="date", type="date", unique=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="ouvertureCaisse", type="decimal", precision=10, scale=3)
     */
    private $ouvertureCaisse;

    /**
     * @var string
     *
     * @ORM\Column(name="fermutureCaisse", type="decimal", precision=10, scale=3)
     */
    private $fermutureCaisse;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ActionCaisse", mappedBy="elementCaisse", cascade={"persist", "remove"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $actionsCaisse;

    public function __construct() {
      $this->date = new \DateTime();
      $this->actionsCaisse = new \Doctrine\Common\Collections\ArrayCollection();
      $this->ouvertureCaisse = 0;
      $this->fermutureCaisse = 0;
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
     * @return ElementCaisse
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
     * Set ouvertureCaisse
     *
     * @param string $ouvertureCaisse
     *
     * @return ElementCaisse
     */
    public function setOuvertureCaisse($ouvertureCaisse)
    {
        $this->ouvertureCaisse = $ouvertureCaisse;

        return $this;
    }

    /**
     * Get ouvertureCaisse
     *
     * @return string
     */
    public function getOuvertureCaisse()
    {
        return $this->ouvertureCaisse;
    }

    /**
     * Set fermutureCaisse
     *
     * @param string $fermutureCaisse
     *
     * @return ElementCaisse
     */
    public function setFermutureCaisse($fermutureCaisse)
    {
        $this->fermutureCaisse = $fermutureCaisse;

        return $this;
    }

    /**
     * Get fermutureCaisse
     *
     * @return string
     */
    public function getFermutureCaisse()
    {
        return $this->fermutureCaisse;
    }

    /**
     * Add actionsCaisse
     *
     * @param \AppBundle\Entity\ActionCaisse $actionsCaisse
     *
     * @return ElementCaisse
     */
    public function addActionsCaisse(\AppBundle\Entity\ActionCaisse $actionsCaisse)
    {
        $this->actionsCaisse[] = $actionsCaisse;
        $this->fermutureCaisse += $actionsCaisse->getMontant();
        return $this;
    }

    /**
     * Remove actionsCaisse
     *
     * @param \AppBundle\Entity\ActionCaisse $actionsCaisse
     */
    public function removeActionsCaisse(\AppBundle\Entity\ActionCaisse $actionsCaisse)
    {
        $this->actionsCaisse->removeElement($actionsCaisse);
        $this->fermutureCaisse -= $actionsCaisse->getMontant();
    }

    /**
     * Get actionsCaisse
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionsCaisse()
    {
        return $this->actionsCaisse;
    }
}
