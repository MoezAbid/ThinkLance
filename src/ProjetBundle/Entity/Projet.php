<?php

namespace ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Projet
 *
 * @ORM\Table(name="projet")
 * @ORM\Entity(repositoryClass="ProjetBundle\Repository\ProjetRepository")
 */
class Projet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idProjet;

    /**
     * @var string
     *
     * @ORM\Column(name="titreprojet", type="string", length=255)
     */
    private $titreProjet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="nbrejours", type="integer")
     */
    private $nbreJours;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="nomfichiers", type="string", length=255)
     */
    private $nomFichiers;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the file as pdf. ")
     *
     * @Assert\File(mimeTypes="application/pdf", maxSize="1000k")
     */
    public $file;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User" )
     * @ORM\JoinColumn(name="employeur", referencedColumnName="id", onDelete="CASCADE")
     */
    private $employeur;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="freelancer", referencedColumnName="id",onDelete="CASCADE")
     */
    private $freelancer;

    /**
     * @ORM\ManyToOne(targetEntity="ProjetBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="IdCategorie", referencedColumnName="id", onDelete="CASCADE")
     */
    private $categorie;

    /**
     * Get idProjet
     *
     * @return integer
     */
    public function getIdProjet()
    {
        return $this->idProjet;
    }

    /**
     * Set titreProjet
     *
     * @param string $titreProjet
     *
     * @return Projet
     */
    public function setTitreProjet($titreProjet)
    {
        $this->titreProjet = $titreProjet;

        return $this;
    }

    /**
     * Get titreProjet
     *
     * @return string
     */
    public function getTitreProjet()
    {
        return $this->titreProjet;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Projet
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set nbreJours
     *
     * @param string $nbreJours
     *
     * @return Projet
     */
    public function setNbreJours($nbreJours)
    {
        $this->nbreJours = $nbreJours;

        return $this;
    }

    /**
     * Get nbreJours
     *
     * @return string
     */
    public function getNbreJours()
    {
        return $this->nbreJours;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Projet
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set nomFichiers
     *
     * @param string $nomFichiers
     *
     * @return Projet
     */
    public function setNomFichiers($nomFichiers)
    {
        $this->nomFichiers = $nomFichiers;

        return $this;
    }

    /**
     * Get nomFichiers
     *
     * @return string
     */
    public function getNomFichiers()
    {
        return $this->nomFichiers;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Projet
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }



    /**
     * Set categorie
     *
     * @param \ProjetBundle\Entity\Categorie $categorie
     *
     * @return Projet
     */
    public function setCategorie(\ProjetBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \ProjetBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }


    /*public function  getWebPath(){
        return null ===$this->nomFichiers ? null : $this->getUploadDir.'/'.$this->nomFichiers;
    }

    protected function getUploadRootDir(){
        return __Dir__.'/../../../web/files'.$this->getUploadDir();
    }
    protected function getUploadDir(){
        return 'files';
    }
    public function uploadFile(){
        $this->f->move($this->getUploadRootDir(),$this->f->getClientOriginalName());
        $this->nomFichiers=$this->f->getClientoriginalName();
        $this->f=null;
    }*/


    /**
     * Set employeur
     *
     * @param \AppBundle\Entity\User $employeur
     *
     * @return Projet
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

    /**
     * Set freelancer
     *
     * @param \AppBundle\Entity\User $freelancer
     *
     * @return Projet
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
}
