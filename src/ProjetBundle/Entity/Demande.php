<?php

namespace ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SBC\NotificationsBundle\Builder\NotificationBuilder;
use SBC\NotificationsBundle\Model\NotifiableInterface;

/**
 * Demande
 *
 * @ORM\Table(name="demande")
 * @ORM\Entity(repositoryClass="ProjetBundle\Repository\DemandeRepository")
 */
class Demande
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateDemande", type="date")
     */
    private $dateDemande;

    /**
     * @var string
     *
     * @ORM\Column(name="etatDemande", type="string", length=255, options={"default" = "en attente"}, nullable=true)
     */
    private $etatDemande;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="IdEmployeur", referencedColumnName="id",nullable=true)
     */
    private $employeur;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="freelancer", referencedColumnName="id",nullable=true)
     */
    private $freelancer;

    /**
     * @ORM\ManyToOne(targetEntity="ProjetBundle\Entity\Projet")
     * @ORM\JoinColumn(name="IdProjet", referencedColumnName="id",nullable=true)
     */
    private $projet;

    /**
     * Build notifications on entity creation
     * @param NotificationBuilder $builder
     * @return NotificationBuilder
     */
  /*  public function notificationsOnCreate(NotificationBuilder $builder)
    {
        $notification = new Notification();
        $notification ->setTitle('contribution projet')
            ->setDescription($this->getEmployeur())
            //->setEmployeur($this->getId())
            ->setFreelancer($this->getFreelancer());
          //  ->setRoute('#');
            //->setParameters(array('id'=>$this->getId()));
        $notification->setEmployeur($this->getEmployeur());
        $builder->addNotification($notification);
        return $builder;
    }*/

    /**
     * Build notifications on entity update
     * @param NotificationBuilder $builder
     * @return NotificationBuilder
     */
    public function notificationsOnUpdate(NotificationBuilder $builder)
    {
        return $builder;
    }

    /**
     * Build notifications on entity delete
     * @param NotificationBuilder $builder
     * @return NotificationBuilder
     */
    public function notificationsOnDelete(NotificationBuilder $builder)
    {
        return $builder;
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
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     *
     * @return Demande
     */
    public function setDateDemande($dateDemande)
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    /**
     * Get dateDemande
     *
     * @return \DateTime
     */
    public function getDateDemande()
    {
        return $this->dateDemande;
    }

    /**
     * Set etatDemande
     *
     * @param string $etatDemande
     *
     * @return Demande
     */
    public function setEtatDemande($etatDemande)
    {
        $this->etatDemande = $etatDemande;

        return $this;
    }

    /**
     * Get etatDemande
     *
     * @return string
     */
    public function getEtatDemande()
    {
        return $this->etatDemande;
    }

    /**
     * Set employeur
     *
     * @param \AppBundle\Entity\User $employeur
     *
     * @return Demande
     */
    public function setEmployeur(\AppBundle\Entity\User $employeur = null)
    {
        $this->employeur = $employeur;

        return $this;
    }

    /**
     * Get employeur
     *
     * @return \AppBundle\Entity\User
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }

    /**
     * Set freelancer
     *
     * @param \AppBundle\Entity\User $freelancer
     *
     * @return Demande
     */
    public function setFreelancer(\AppBundle\Entity\User $freelancer = null)
    {
        $this->freelancer = $freelancer;

        return $this;
    }

    /**
     * Get freelancer
     *
     * @return \AppBundle\Entity\User
     */
    public function getFreelancer()
    {
        return $this->freelancer;
    }

    /**
     * Set projet
     *
     * @param \ProjetBundle\Entity\Projet $projet
     *
     * @return Demande
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
