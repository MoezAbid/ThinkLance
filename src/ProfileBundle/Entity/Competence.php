<?php

namespace ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Competence
 *
 * @ORM\Table(name="competence")
 * @ORM\Entity(repositoryClass="ProfileBundle\Repository\CompetenceRepository")
 */
class Competence
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
     *
     * @ORM\Column(name="nom_competence", type="string", length=255, unique=true,nullable=true)
     */
    private $nomCompetence;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     *
     */
    private $user;
    /**
     * @ORM\ManyToMany(targetEntity="ProfileBundle\Entity\Tag",cascade={"persist"})
     *
     */
    private $tags;
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
     * Set nomCompetence
     *
     * @param string $nomCompetence
     *
     * @return Competence
     */
    public function setNomCompetence($nomCompetence)
    {
        $this->nomCompetence = $nomCompetence;

        return $this;
    }

    /**
     * Get nomCompetence
     *
     * @return string
     */
    public function getNomCompetence()
    {
        return $this->nomCompetence;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tag
     *
     * @param \ProfileBundle\Entity\Tag $tag
     *
     * @return Competence
     */
    public function addTag(\ProfileBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \ProfileBundle\Entity\Tag $tag
     */
    public function removeTag(\ProfileBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Competence
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
}
