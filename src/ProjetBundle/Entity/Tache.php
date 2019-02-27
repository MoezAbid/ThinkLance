<?php

namespace ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * tache
 *
 * @ORM\Table(name="tache")
 * @ORM\Entity(repositoryClass="ProjetBundle\Repository\TacheRepository")
 */
class Tache
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
     * @ORM\Column(name="nomTache", type="string", length=255)
     */
    private $nomTache;

    /**
     * @var string
     *
     * @ORM\Column(name="etatTache", type="string", length=255,nullable=true)
     */
    private $etatTache;

    /**
     * @var string
     *
     * @ORM\Column(name="typeTache", type="string", length=255,nullable=true)
     */
    private $typeTache;

    /**
     * @var string
     *
     * @ORM\Column(name="estimationTache", type="string", length=255,nullable=true)
     */
    private $estimationTache;

    /**
     * @var string
     *
     * @ORM\Column(name="prioriteTache", type="integer", length=255,nullable=true)
     */
    private $prioriteTache;
    /**
     * @ORM\ManyToOne(targetEntity="ProjetBundle\Entity\Projet")
     * @ORM\JoinColumn(name="IdProjet", referencedColumnName="id")
     */
    private $projet;







    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomTache
     *
     * @param string $nomTache
     *
     * @return Tache
     */
    public function setNomTache($nomTache)
    {
        $this->nomTache = $nomTache;

        return $this;
    }

    /**
     * Get nomTache
     *
     * @return string
     */
    public function getNomTache()
    {
        return $this->nomTache;
    }

    /**
     * Set etatTache
     *
     * @param string $etatTache
     *
     * @return Tache
     */
    public function setEtatTache($etatTache)
    {
        $this->etatTache = $etatTache;

        return $this;
    }

    /**
     * Get etatTache
     *
     * @return string
     */
    public function getEtatTache()
    {
        return $this->etatTache;
    }

    /**
     * Set typeTache
     *
     * @param string $typeTache
     *
     * @return Tache
     */
    public function setTypeTache($typeTache)
    {
        $this->typeTache = $typeTache;

        return $this;
    }

    /**
     * Get typeTache
     *
     * @return string
     */
    public function getTypeTache()
    {
        return $this->typeTache;
    }

    /**
     * Set estimationTache
     *
     * @param string $estimationTache
     *
     * @return Tache
     */
    public function setEstimationTache($estimationTache)
    {
        $this->estimationTache = $estimationTache;

        return $this;
    }

    /**
     * Get estimationTache
     *
     * @return string
     */
    public function getEstimationTache()
    {
        return $this->estimationTache;
    }

    /**
     * Set prioriteTache
     *
     * @param integer $prioriteTache
     *
     * @return Tache
     */
    public function setPrioriteTache($prioriteTache)
    {
        $this->prioriteTache = $prioriteTache;

        return $this;
    }

    /**
     * Get prioriteTache
     *
     * @return integer
     */
    public function getPrioriteTache()
    {
        return $this->prioriteTache;
    }

    /**
     * Set projet
     *
     * @param \ProjetBundle\Entity\Projet $projet
     *
     * @return Tache
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
}
