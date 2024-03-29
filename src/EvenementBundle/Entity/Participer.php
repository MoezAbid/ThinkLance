<?php

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participer
 *
 * @ORM\Table(name="participer")
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\ParticiperRepository")
 */
class Participer
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
     * @ORM\ManyToOne(targetEntity="EvenementBundle\Entity\Evenement")
     *@ORM\JoinColumn(name="Idevent",referencedColumnName="id")
     *
     */
    private $idevent;
    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *@ORM\JoinColumn(name="iduser",referencedColumnName="id")
     *
     */
    private $iduser;
    /**
     * @var int
     *
     * @ORM\Column(name="event", type="integer")
     */
    private $event;
    /**
     * @var int
     *
     * @ORM\Column(name="user", type="integer")
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
     * Set iduser
     *
     * @param \AppBundle\Entity\User $iduser
     *
     * @return Participer
     */
    public function setIduser(\AppBundle\Entity\User $iduser = null)
    {
        $this->iduser = $iduser;

        return $this;
    }

    /**
     * Get iduser
     *
     * @return \AppBundle\Entity\User
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * Set idevent
     *
     * @param \EvenementBundle\Entity\Evenement $idevent
     *
     * @return Participer
     */
    public function setIdevent(\EvenementBundle\Entity\Evenement $idevent = null)
    {
        $this->idevent = $idevent;

        return $this;
    }

    /**
     * Get idevent
     *
     * @return \EvenementBundle\Entity\Evenement
     */
    public function getIdevent()
    {
        return $this->idevent;
    }

    /**
     * Set event
     *
     * @param integer $event
     *
     * @return Participer
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return integer
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Participer
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }
}
