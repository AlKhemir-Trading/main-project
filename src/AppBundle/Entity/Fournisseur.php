<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fournisseur
 *
 * @ORM\Table(name="fournisseur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FournisseurRepository")
 */
class Fournisseur
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
     * @Assert\NotBlank(message=" Le nom du Fournisseur est obligatoire!")
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=50)
     */
    private $pays;

    /**
     * @var int
     *
     * @ORM\Column(name="tel", type="bigint", nullable=true)
     */
    private $tel;

    /**
     * @var int
     *
     * @ORM\Column(name="date_creation", type="date", nullable=false)
     */
    private $dateCreation;

    /**
     * @var Arrivage
     *
     * @ORM\OneToMany(targetEntity="Arrivage", mappedBy="fournisseur")
     */
    private $arrivages;

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
     * @return Fournisseur
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
     * Set pays
     *
     * @param string $pays
     *
     * @return Fournisseur
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set tel
     *
     * @param integer $tel
     *
     * @return Fournisseur
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return int
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Fournisseur
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
     * Add arrivage
     *
     * @param \AppBundle\Entity\Arrivage $arrivage
     *
     * @return Fournisseur
     */
    public function addArrivage(\AppBundle\Entity\Arrivage $arrivage)
    {
        $this->arrivages[] = $arrivage;

        return $this;
    }

    /**
     * Remove arrivage
     *
     * @param \AppBundle\Entity\Arrivage $arrivage
     */
    public function removeArrivage(\AppBundle\Entity\Arrivage $arrivage)
    {
        $this->arrivages->removeElement($arrivage);
    }

    /**
     * Get arrivages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArrivages()
    {
        return $this->arrivages;
    }
}
