<?php

namespace ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SBC\NotificationsBundle\Model\BaseNotification;

/**
 * Notification
 *
 * @ORM\Table(name="notificationdemande")
 * @ORM\Entity(repositoryClass="ProjetBundle\Repository\NotificationRepository")
 */
class Notification extends BaseNotification implements \JsonSerializable
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
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="IdEmployeur", referencedColumnName="id")
     */
    private $employeur;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="freelancer", referencedColumnName="id", nullable=true)
     */
    private $freelancer;


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
     * @return mixed
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }

    /**
     * @param mixed $employeur
     */
    public function setEmployeur($employeur)
    {
        $this->employeur = $employeur;
    }

    /**
     * @return mixed
     */
    public function getFreelancer()
    {
        return $this->freelancer;
    }

    /**
     * @param mixed $freelancer
     */
    public function setFreelancer($freelancer)
    {
        $this->freelancer = $freelancer;
    }


}

