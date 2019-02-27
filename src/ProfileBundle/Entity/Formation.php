<?php

namespace ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Formation
 *
 * @ORM\Table(name="formation")
 * @ORM\Entity(repositoryClass="ProfileBundle\Repository\FormationRepository")
 */
class Formation
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
     * @Assert\NotBlank(message="Veuillez saisir le titre de la formation")
     * @ORM\Column(name="titre", type="string", length=255,nullable=true)
     */
    private $titre;

    /**
     * @var datetime
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "La date n'est pas valide",
     * )
     * @ORM\Column(name="DateDebut", type="datetime",nullable=true, unique=true)
     */
    private $dateDebut;

    /**
     * @var datetime
     *
     * @ORM\Column(name="DateFin", type="datetime",nullable=true)
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "La date n'est pas valide",
     * )
     * @Assert\Expression(
     *     "this.getDateFin() >= this.getDateDebut()",
     *     message="La date de fin formation doit être supérieur à la date de début de formation"
     * )
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="institution", type="string", length=255,nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir l'institution")
     */
    private $institution;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez décrire votre formation")
     * @ORM\Column(name="description", type="string", length=500,nullable=true)
     */
    private $description;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     *
     */
    private $user;

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Formation
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }


    /**
     * Set institution
     *
     * @param string $institution
     *
     * @return Formation
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return string
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Formation
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Formation
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Formation
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Formation
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
}
