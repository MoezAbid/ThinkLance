<?php

namespace ReclamationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SBC\NotificationsBundle\Builder\NotificationBuilder;
use SBC\NotificationsBundle\Model\NotifiableInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * reclamations
 *
 * @ORM\Table(name="reclamations")
 * @ORM\Entity(repositoryClass="ReclamationBundle\Repository\reclamationsRepository")
 */
class reclamations implements NotifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=255)
     *  @Assert\NotBlank()
     *  * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     * @Assert\NotBlank()
     *
     *  * @Assert\Length(
     *      min = 30,
     *      max = 100,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemc", type="date")
     */
    private $datemc;
    /**
     * @ORM\ManyToOne(targetEntity="ReclamationBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="nomCateg",referencedColumnName="id")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="nomuser",referencedColumnName="id")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="nomadmin",referencedColumnName="id")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $admin;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     *
     *
     */
    private $etat;


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
     * Set sujet
     *
     * @param string $sujet
     *
     * @return reclamations
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return reclamations
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set categorie
     *
     * @param \ReclamationBundle\Entity\Categorie $categorie
     *
     * @return reclamations
     */
    public function setCategorie(\ReclamationBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \ReclamationBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }





    /**
     * Set datemc
     *
     * @param \DateTime $datemc
     *
     * @return reclamations
     */
    public function setDatemc($datemc)
    {
        $this->datemc = $datemc;

        return $this;
    }

    /**
     * Get datemc
     *
     * @return \DateTime
     */
    public function getDatemc()
    {
        return $this->datemc;
    }


    /**
     * Build notifications on entity creation
     * @param NotificationBuilder $builder
     * @return NotificationBuilder
     */
    public function notificationsOnCreate(NotificationBuilder $builder)
    {//$this->get('sujet')->getData()
        $notif=new Notifications();
        $notif->setTitle($this->getSujet())
            ->setDescription($this->getMessage())
            ->setRoute('reclamation_homepage')
            ->setParameters(array('id'=>$this->getId()));
        $builder->addNotification($notif);
        return $builder;

    }

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
     * Set etat
     *
     * @param string $etat
     *
     * @return reclamations
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return reclamations
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
     * Set admin
     *
     * @param \AppBundle\Entity\User $admin
     *
     * @return reclamations
     */
    public function setAdmin(\AppBundle\Entity\User $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return \AppBundle\Entity\User
     */
    public function getAdmin()
    {
        return $this->admin;
    }
}
