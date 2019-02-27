<?php

namespace ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SBC\NotificationsBundle\Model\BaseNotification;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="ProfileBundle\Repository\NotificationRepository")
 */
class Notification extends BaseNotification implements  \JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_employeur", referencedColumnName="id")
     *
     */
    private $idEmployer;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_freelancer", referencedColumnName="id")
     *
     */
    private $idFreelancer;








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
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * Get seen
     *
     * @return boolean
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set idEmployer
     *
     * @param \AppBundle\Entity\User $idEmployer
     *
     * @return Notification
     */
    public function setIdEmployer(\AppBundle\Entity\User $idEmployer = null)
    {
        $this->idEmployer = $idEmployer;

        return $this;
    }

    /**
     * Get idEmployer
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdEmployer()
    {
        return $this->idEmployer;
    }

    /**
     * Set idFreelancer
     *
     * @param \AppBundle\Entity\User $idFreelancer
     *
     * @return Notification
     */
    public function setIdFreelancer(\AppBundle\Entity\User $idFreelancer = null)
    {
        $this->idFreelancer = $idFreelancer;

        return $this;
    }

    /**
     * Get idFreelancer
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdFreelancer()
    {
        return $this->idFreelancer;
    }
}
