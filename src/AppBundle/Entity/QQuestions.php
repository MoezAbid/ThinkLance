<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * QQuestions
 *
 * @ORM\Table(name="qquestions", indexes={@ORM\Index(name="id_user2", columns={"idU"}), @ORM\Index(name="clFK2", columns={"idC"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuestionRepository")
 */
class QQuestions
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
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=256, nullable=false)
     * @Assert\Length(min=3)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=256, nullable=false)
     * @Assert\Length(min=3)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="postDate", type="date", nullable=false)
     */
    private $postdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="answered", type="date", nullable=true)
     */
    private $answered;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=256, nullable=false)
     */
    private $subject;

    /**
     * @var integer
     *
     * @ORM\Column(name="replies", type="integer", nullable=true)
     */
    private $replies;
    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="integer", nullable=true)
     */
    private $views;
    /**
     * @var integer
     *
     * @ORM\Column(name="signaler", type="integer", nullable=true)
     */
    private $signaler;

    /**
     * @var \QClouds
     *
     * @ORM\ManyToOne(targetEntity="QClouds")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idC", referencedColumnName="id")
     * })
     */
    private $idc;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *   @ORM\JoinColumn(name="idU", referencedColumnName="id")
     */

    private $idu;
    /**
     * QQuestion constructor
     */
public function __construct()
    {
        $this->postdate=new \DateTime('now');
        $this->replies=0;
        $this->signaler=0;
        $this->views=0;
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
     * Set question
     *
     * @param string $question
     *
     * @return QQuestions
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return QQuestions
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set postdate
     *
     * @param \DateTime $postdate
     *
     * @return QQuestions
     */
    public function setPostdate($postdate)
    {
        $this->postdate = $postdate;

        return $this;
    }

    /**
     * Get postdate
     *
     * @return \DateTime
     */
    public function getPostdate()
    {
        return $this->postdate;
    }

    /**
     * Set answered
     *
     * @param \DateTime $answered
     *
     * @return QQuestions
     */
    public function setAnswered($answered)
    {
        $this->answered = $answered;

        return $this;
    }

    /**
     * Get answered
     *
     * @return \DateTime
     */
    public function getAnswered()
    {
        return $this->answered;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return QQuestions
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set replies
     *
     * @param integer $replies
     *
     * @return QQuestions
     */
    public function setReplies($replies)
    {
        $this->replies = $replies;

        return $this;
    }

    /**
     * Get replies
     *
     * @return integer
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * Set signaler
     *
     * @param integer $signaler
     *
     * @return QQuestions
     */
    public function setSignaler($signaler)
    {
        $this->signaler = $signaler;

        return $this;
    }

    /**
     * Get signaler
     *
     * @return integer
     */
    public function getSignaler()
    {
        return $this->signaler;
    }

    /**
     * Set views
     *
     * @param integer $views
     *
     * @return QReply
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    public function incrementReplies(){
        $this->replies +=1;
        return $this->replies;
    }
    public function decrementReplies(){
        $this->replies -=1;
        return $this->replies;
    }
    public function incrementSignal(){
        $this->signaler +=1;
        return $this;
    }
    public function incrementViews(){
        $this->views +=1;
    }
    /**
     * Set idc
     *
     * @param \AppBundle\Entity\QClouds $idc
     *
     * @return QQuestions
     */
    public function setIdc(\AppBundle\Entity\QClouds $idc = null)
    {
        $this->idc = $idc;

        return $this;
    }

    /**
     * Get idc
     *
     * @return \AppBundle\Entity\QClouds
     */
    public function getIdc()
    {
        return $this->idc;
    }



    /**
     * Set idu
     *
     * @param \AppBundle\Entity\User $idu
     *
     * @return QQuestions
     */
    public function setIdu(\AppBundle\Entity\User $idu = null)
    {
        $this->idu = $idu;

        return $this;
    }

    /**
     * Get idu
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdu()
    {
        return $this->idu;
    }
}
