<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Event\PreUpdateEventArgs;
/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Client
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
     * @Assert\NotBlank(message=" Le nom du Client est obligatoire!")
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
     * @var ventes
     *
     * @ORM\OneToMany(targetEntity="Vente", mappedBy="client")
     */
    private $ventes;

    //cascade={"persist", "remove"}, orphanRemoval=TRUE
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Payement", mappedBy="client")
     */
    private $payements;

    /**
     * @var string
     *
     * @ORM\Column(name="plus_ou_moins", type="decimal", precision=10, scale=3 , nullable=true)
     */
    private $plusOuMoins;

    public function __construct() {
      $this->dateCreation = new \DateTime();
      $this->payements = new \Doctrine\Common\Collections\ArrayCollection();
      $this->plusOuMoins = 0;
    }

    // /**
    //  * @ORM\PreUpdate
    //  */
    //  public function preUpdate(PreUpdateEventArgs $event)
    // {
    //     // if ($event->hasChangedField('tel')) {
    //     //     die('old'.$event->getOldValue('tel')."<br />".$event->getNewValue('tel'));
    //     // }
    //     die('client update');
    // }

    // /**
    //  * @ORM\PostLoad
    //  */
    // public function postRefresh()
    // {
    //   die('Refresh');
    // }

    public function updatePlusOuMoins(){
      //echo "<br />UPDATEO".$this->getId()."<br />";
      $totalVendu = 0;
      foreach( $this->getVentes() as $vente){
        $totalVendu += $vente->getMontant();
        // echo "<br />".$vente->getMontant()."<br />";
        // echo $vente->getMontant()."<br />";
      }
// die;
      $totalPaye = 0;
      foreach( $this->getPayements() as $payement)
        $totalPaye += $payement->getMontant();

      // die($totalPaye ."/". $totalVendu);
      // echo $totalPaye ."/". $totalVendu;
      $this->plusOuMoins = $totalPaye - $totalVendu;
      //echo "<br />AA:".$this->plusOuMoins."<br />";
      // echo "paye".$totalPaye."  vendu".$totalVendu."<br />";
      // echo "+-".$this->plusOuMoins."<br />";
      // die("qqqq");
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * Add vente
     *
     * @param \AppBundle\Entity\Vente $vente
     *
     * @return Client
     */
    public function addVente(\AppBundle\Entity\Vente $vente)
    {
        $this->ventes[] = $vente;

        return $this;
    }

    /**
     * Remove vente
     *
     * @param \AppBundle\Entity\Vente $vente
     */
    public function removeVente(\AppBundle\Entity\Vente $vente)
    {
        $this->ventes->removeElement($vente);
    }

    /**
     * Get ventes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVentes()
    {
        return $this->ventes;
    }

    /**
     * Add payement
     *
     * @param \AppBundle\Entity\Payement $payement
     *
     * @return Client
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

    /**
     * Set plusOuMoins
     *
     * @param string $plusOuMoins
     *
     * @return Client
     */
    public function setPlusOuMoins($plusOuMoins)
    {
        $this->plusOuMoins = $plusOuMoins;

        return $this;
    }

    /**
     * Get plusOuMoins
     *
     * @return string
     */
    public function getPlusOuMoins()
    {
        return $this->plusOuMoins;
    }
}
