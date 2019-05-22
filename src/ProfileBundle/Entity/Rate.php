<?php

namespace ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rate")
 * @ORM\Entity(repositoryClass="ProfileBundle\Repository\RateRepository")
 */
class Rate
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
     * @var int
     *
     * @ORM\Column(name="rating_qualite", type="integer", nullable=true)
     */
    private $ratingQualite;

    /**
     * @var int
     *
     * @ORM\Column(name="rating_communication", type="integer", nullable=true)
     */
    private $ratingCommunication;

    /**
     * @var int
     *
     * @ORM\Column(name="rating_respect_delais", type="integer", nullable=true)
     */
    private $ratingRespectDelais;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_employeur", referencedColumnName="id")
     *
     */
    private $idEmployeur;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_freelancer", referencedColumnName="id")
     *
     */
    private $idFrellancer;

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
     * Set ratingQualite
     *
     * @param integer $ratingQualite
     *
     * @return Rate
     */
    public function setRatingQualite($ratingQualite)
    {
        $this->ratingQualite = $ratingQualite;

        return $this;
    }

    /**
     * Get ratingQualite
     *
     * @return integer
     */
    public function getRatingQualite()
    {
        return $this->ratingQualite;
    }

    /**
     * Set ratingCommunication
     *
     * @param integer $ratingCommunication
     *
     * @return Rate
     */
    public function setRatingCommunication($ratingCommunication)
    {
        $this->ratingCommunication = $ratingCommunication;

        return $this;
    }

    /**
     * Get ratingCommunication
     *
     * @return integer
     */
    public function getRatingCommunication()
    {
        return $this->ratingCommunication;
    }

    /**
     * Set ratingRespectDelais
     *
     * @param integer $ratingRespectDelais
     *
     * @return Rate
     */
    public function setRatingRespectDelais($ratingRespectDelais)
    {
        $this->ratingRespectDelais = $ratingRespectDelais;

        return $this;
    }

    /**
     * Get ratingRespectDelais
     *
     * @return integer
     */
    public function getRatingRespectDelais()
    {
        return $this->ratingRespectDelais;
    }

    /**
     * Set idEmployeur
     *
     * @param \AppBundle\Entity\User $idEmployeur
     *
     * @return Rate
     */
    public function setIdEmployeur(\AppBundle\Entity\User $idEmployeur = null)
    {
        $this->idEmployeur = $idEmployeur;

        return $this;
    }

    /**
     * Get idEmployeur
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdEmployeur()
    {
        return $this->idEmployeur;
    }

    /**
     * Set idFrellancer
     *
     * @param \AppBundle\Entity\User $idFrellancer
     *
     * @return Rate
     */
    public function setIdFrellancer(\AppBundle\Entity\User $idFrellancer = null)
    {
        $this->idFrellancer = $idFrellancer;

        return $this;
    }

    /**
     * Get idFrellancer
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdFrellancer()
    {
        return $this->idFrellancer;
    }
}
