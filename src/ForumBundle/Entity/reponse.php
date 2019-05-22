<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * reponse
 *
 * @ORM\Table(name="reponse")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\reponseRepository")
 */
class reponse
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
     * @ORM\Column(name="contenur", type="string", length=255)
     */
    private $contenur;

    /**
     * @var \DateTime
     * @Assert\GreaterThan("today")
     * @ORM\Column(name="date_rc", type="date")
     */
    private $dateRc;


    /**
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\question")
     * @ORM\JoinColumn(name="IDquestion",referencedColumnName="id")
     */

    private $question;


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
     * Set contenur
     *
     * @param string $contenur
     *
     * @return reponse
     */
    public function setContenur($contenur)
    {
        $this->contenur = $contenur;

        return $this;
    }

    /**
     * Get contenur
     *
     * @return string
     */
    public function getContenur()
    {
        return $this->contenur;
    }

    /**
     * Set dateRc
     *
     * @param \DateTime $dateRc
     *
     * @return reponse
     */
    public function setDateRc($dateRc)
    {
        $this->dateRc = $dateRc;

        return $this;
    }

    /**
     * Get dateRc
     *
     * @return \DateTime
     */
    public function getDateRc()
    {
        return $this->dateRc;
    }

    /**
     * Set question
     *
     * @param \ForumBundle\Entity\question $question
     *
     * @return reponse
     */
    public function setQuestion(\ForumBundle\Entity\question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \ForumBundle\Entity\question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
