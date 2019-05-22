<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QViews
 *
 * @ORM\Table(name="qviews", indexes={@ORM\Index(name="usrFK1", columns={"idU"})})
 * @ORM\Entity
 */
class QViews
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="view_identification", type="integer", nullable=false)
     */
    private $viewIdentification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateView", type="date", nullable=false)
     */
    private $dateview;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *   @ORM\JoinColumn(name="idU", referencedColumnName="id")
     *
     */
    private $idu;

    public function __construct()
    {
        $this->dateview = new \DateTime('now');

    }

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
     * Set type
     *
     * @param integer $type
     *
     * @return QViews
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set viewIdentification
     *
     * @param integer $viewIdentification
     *
     * @return QViews
     */
    public function setViewIdentification($viewIdentification)
    {
        $this->viewIdentification = $viewIdentification;

        return $this;
    }

    /**
     * Get viewIdentification
     *
     * @return integer
     */
    public function getViewIdentification()
    {
        return $this->viewIdentification;
    }

    /**
     * Set dateview
     *
     * @param \DateTime $dateview
     *
     * @return QViews
     */
    public function setDateview($dateview)
    {
        $this->dateview = $dateview;

        return $this;
    }

    /**
     * Get dateview
     *
     * @return \DateTime
     */
    public function getDateview()
    {
        return $this->dateview;
    }

    /**
     * Set idu
     *
     * @param \AppBundle\Entity\FosUser $idu
     *
     * @return QViews
     */
    public function setIdu(\AppBundle\Entity\FosUser $idu = null)
    {
        $this->idu = $idu;

        return $this;
    }

    /**
     * Get idu
     *
     * @return \AppBundle\Entity\FosUser
     */
    public function getIdu()
    {
        return $this->idu;
    }
}
