<?php
namespace PaiementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Paiement
 *
 * @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass="PaiementBundle\Repository\PaiementRepository")
 */
class Paiement
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="IdPaiement", type="string")
     */
    private $idPaiement;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHeurePaiement", type="datetime")
     */
    private $dateHeurePaiement;
    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;
    //Foreign key projet:
    /**
     * @ORM\ManyToOne(targetEntity="ProjetBundle\Entity\Projet")
     * @ORM\JoinColumn(name="Projet", referencedColumnName="IdProjet", onDelete="SET NULL")
     *
     */
    private $projet;
    //Foreign key IdFreelancer:
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="freelancer", referencedColumnName="id", onDelete="SET NULL")
     *
     */
    private $freelancer;
    //Foreign key IdEmployeur:
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="employeur", referencedColumnName="id", onDelete="SET NULL")
     *
     */
    private $employeur;
    /**
     * Set idPaiement
     *
     * @param integer $idPaiement
     *
     * @return Paiement
     */
    public function setIdPaiement($idPaiement)
    {
        $this->idPaiement = $idPaiement;
        return $this;
    }
    /**
     * Get idPaiement
     *
     * @return string
     */
    public function getIdPaiement()
    {
        return $this->idPaiement;
    }
    /**
     * Set dateHeurePaiement
     *
     * @param \DateTime $dateHeurePaiement
     *
     * @return Paiement
     */
    public function setDateHeurePaiement($dateHeurePaiement)
    {
        $this->dateHeurePaiement = $dateHeurePaiement;
        return $this;
    }
    /**
     * Get dateHeurePaiement
     *
     * @return \DateTime
     */
    public function getDateHeurePaiement()
    {
        return $this->dateHeurePaiement;
    }
    /**
     * Set montant
     *
     * @param float $montant
     *
     * @return Paiement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
        return $this;
    }
    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }
    //Foreign Key Projet :
    /**
     * Set projet
     *
     * @param \ProjetBundle\Entity\Projet $projet
     *
     * @return Paiement
     */
    public function setProjet(\ProjetBundle\Entity\Projet $projet = null)
    {
        $this->projet = $projet;
        return $this;
    }
    /**
     * Get projet
     *
     * @return \ProjetBundle\Entity\Projet
     */
    public function getProjet()
    {
        return $this->projet;
    }
    //Foreign Key Freelancer :
    /**
     * Set freelancer
     *
     * @param \AppBundle\Entity\User $freelancer
     *
     * @return Paiement
     */
    public function setFreelancer(\AppBundle\Entity\User $freelancer = null)
    {
        $this->freelancer = $freelancer;
        return $this;
    }
    /**
     * Get freelancer
     *
     * @return \AppBundle\Entity\User
     */
    public function getFreelancer()
    {
        return $this->freelancer;
    }
    //Foreign Key Employeur :
    /**
     * Set employeur
     *
     * @param \AppBundle\Entity\User $employeur
     *
     * @return Paiement
     */
    public function setEmployeur(\AppBundle\Entity\User $employeur = null)
    {
        $this->employeur = $employeur;
        return $this;
    }
    /**
     * Get employeur
     *
     * @return \AppBundle\Entity\User
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }
}