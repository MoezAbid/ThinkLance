<?php

namespace ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ExperiencePro
 *
 * @ORM\Table(name="experience_pro")
 * @ORM\Entity(repositoryClass="ProfileBundle\Repository\ExperienceProRepository")
 */
class ExperiencePro
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
     * @Assert\NotBlank(message="Veuillez saisir le titre de l'expérience")
     * @ORM\Column(name="titre_exp", type="string", length=255, nullable=true)
     */
    private $titreExp;

    /**
     * @var string
     *
     * @ORM\Column(name="DateDebut", type="string", nullable=true, unique=true)
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "La date n'est pas valide",
     * )
     */
    private $dateDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="DateFin", type="string", nullable=true)
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
     * @Assert\NotBlank(message="Veuillez saisir l'entreprise")
     * @ORM\Column(name="entreprise", type="string", length=255, nullable=true)
     */
    private $entreprise;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez décrire l'expérience")
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
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
     * Set titreExp
     *
     * @param string $titreExp
     *
     * @return ExperiencePro
     */
    public function setTitreExp($titreExp)
    {
        $this->titreExp = $titreExp;

        return $this;
    }

    /**
     * Get titreExp
     *
     * @return string
     */
    public function getTitreExp()
    {
        return $this->titreExp;
    }



    /**
     * Set entreprise
     *
     * @param string $entreprise
     *
     * @return ExperiencePro
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get entreprise
     *
     * @return string
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ExperiencePro
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
     * @return ExperiencePro
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
     * @param string $dateDebut
     *
     * @return ExperiencePro
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return string
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param string $dateFin
     *
     * @return ExperiencePro
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return string
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
}
