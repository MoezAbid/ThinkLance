<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    private $firstName;
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $checkprofile;
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $nomImage;
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $specialite;
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $nom_entreprise;
    /**
     * @Assert\NotBlank(message="Veuillez insérer une image.")
     * @Assert\File(maxSize="500k")
     */
    public $file;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un titre pour votre profil")
     * @Assert\Length(
     * min = 5,
     * minMessage = "Le titre de votre profil doit avoir au minimum cinq caractères"
     * )
     */
    private $titreProfile;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un pays")
     */
    private $pays;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir une ville")
     */
    private $ville;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $nbrPoints;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $nbrMission;
    /**
     * @ORM\Column(type="string",nullable=true,length=500)
     * @Assert\NotBlank(message="Veuillez décrire votre profil")
     */
    private $description;
    /**
     * @ORM\Column(type="float",nullable=true,length=500)
     */
    private $note;
    /**
     * @ORM\Column(type="float",nullable=true)
     * @Assert\Regex(
     *     pattern="/^[0-9]+$/",
     *     message="Le prix ne doit pas contenir des lettres"
     * )
     */
    private $tarifMoyen;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $tel;
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $disponibilite;

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

/*    public function getWebPath(){
        return null===$this->nomImage ? null : $this->getUploadDir.'/'.$this->nomImage;
    }

    protected function getUploadRootDir(){
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }
    protected function getUploadDir(){
        return 'images1';
    }
    public function uploadProfilePicture(){
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        $this->nomImage=$this->file->getClientOriginalName();
        $this->file=null;
    }*/

//    /**
//     * Set nomImage
//     *
//     * @param string $nomImage
//     *
//     * @return Categorie
//     */
//    public function setNomImage($nomImage){
//        $this->nomImage==$nomImage;
//        return $this;
//    }
//
//    /**
//     * Get nomImage
//     *
//     * @return string
//     */
//    public function getNomImage(){
//        return $this->nomImage;
//    }

    /**
     * Set titreProfile
     *
     * @param string $titreProfile
     *
     * @return User
     */
    public function setTitreProfile($titreProfile)
    {
        $this->titreProfile = $titreProfile;

        return $this;
    }

    /**
     * Get titreProfile
     *
     * @return string
     */
    public function getTitreProfile()
    {
        return $this->titreProfile;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return User
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
     * Set ville
     *
     * @param string $ville
     *
     * @return User
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set nbrMission
     *
     * @param integer $nbrMission
     *
     * @return User
     */
    public function setNbrMission($nbrMission)
    {
        $this->nbrMission = $nbrMission;

        return $this;
    }

    /**
     * Get nbrMission
     *
     * @return integer
     */
    public function getNbrMission()
    {
        return $this->nbrMission;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
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
     * Set tarifMoyen
     *
     * @param float $tarifMoyen
     *
     * @return User
     */
    public function setTarifMoyen($tarifMoyen)
    {
        $this->tarifMoyen = $tarifMoyen;

        return $this;
    }

    /**
     * Get tarifMoyen
     *
     * @return float
     */
    public function getTarifMoyen()
    {
        return $this->tarifMoyen;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return User
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }



    /**
     * Set checkprofile
     *
     * @param boolean $checkprofile
     *
     * @return User
     */
    public function setCheckprofile($checkprofile)
    {
        $this->checkprofile = $checkprofile;

        return $this;
    }

    /**
     * Get checkprofile
     *
     * @return boolean
     */
    public function getCheckprofile()
    {
        return $this->checkprofile;
    }



    /**
     * Set disponibilite
     *
     * @param boolean $disponibilite
     *
     * @return User
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * Get disponibilite
     *
     * @return boolean
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Set note
     *
     * @param float $note
     *
     * @return User
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return float
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set nbrPoints
     *
     * @param integer $nbrPoints
     *
     * @return User
     */
    public function setNbrPoints($nbrPoints)
    {
        $this->nbrPoints = $nbrPoints;

        return $this;
    }

    /**
     * Get nbrPoints
     *
     * @return integer
     */
    public function getNbrPoints()
    {
        return $this->nbrPoints;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        return array_unique($roles);
    }
    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'firstName'=>$this->getFirstName(),
            'TarifMoyen'=>$this->getTarifMoyen(),
            'TitreProfile'=>$this->getTitreProfile(),
            'ville'=>$this->getVille(),
            'pays'=>$this->getPays(),
            'disponibilite'=>$this->getDisponibilite(),
            'nomImage'=>$this->getNomImage(),
            'roles'=>$this->getRoles(),
            'checkprofile'=>$this->getCheckprofile()


        );
    }

    /**
     * Set specialite
     *
     * @param string $specialite
     *
     * @return User
     */
    public function setSpecialite($specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return string
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set nomEntreprise
     *
     * @param string $nomEntreprise
     *
     * @return User
     */
    public function setNomEntreprise($nomEntreprise)
    {
        $this->nom_entreprise = $nomEntreprise;

        return $this;
    }

    /**
     * Get nomEntreprise
     *
     * @return string
     */
    public function getNomEntreprise()
    {
        return $this->nom_entreprise;
    }
    public function getUsername()
    {
        return parent::getUsername();
    }

    public function  setPassword($password)
    {
        return parent::setPassword($password);
    }
    public function getEmail()
    {
        return parent::getEmail();
    }

    /**
     * Set nomImage
     *
     * @param string $nomImage
     *
     * @return User
     */
    public function setNomImage($nomImage)
    {
        $this->nomImage = $nomImage;

        return $this;
    }

    /**
     * Get nomImage
     *
     * @return string
     */
    public function getNomImage()
    {
        return $this->nomImage;
    }
}
