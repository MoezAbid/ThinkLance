<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\questionRepository")
 */
class question
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
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;

    /**
     * @var \DateTime
     * @Assert\GreaterThan("today")
     * @ORM\Column(name="date_qc", type="date")
     */
    private $dateQc;


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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return question
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set dateQc
     *
     * @param \DateTime $dateQc
     *
     * @return question
     */
    public function setDateQc($dateQc)
    {
        $this->dateQc = $dateQc;

        return $this;
    }

    /**
     * Get dateQc
     *
     * @return \DateTime
     */
    public function getDateQc()
    {
        return $this->dateQc;
    }
}

