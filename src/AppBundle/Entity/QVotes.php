<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QVotes
 *
 * @ORM\Table(name="qvotes", indexes={@ORM\Index(name="userVFK", columns={"idU"}), @ORM\Index(name="repvFK", columns={"idR"})})
 * @ORM\Entity
 */
class QVotes
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
     * @var \DateTime
     *
     * @ORM\Column(name="voteDate", type="date", nullable=false)
     */
    private $votedate;

    /**
     * @var \QReply
     *
     * @ORM\ManyToOne(targetEntity="QReply")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idR", referencedColumnName="id")
     * })
     */
    private $idr;

    /**
     * @var \QQuestions
     *
     * @ORM\ManyToOne(targetEntity="QQuestions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idQ", referencedColumnName="id")
     * })
     */
    private $idq;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *   @ORM\JoinColumn(name="idU", referencedColumnName="id")
     *
     */
    private $idu;

    public function __construct()
    {
        $this->votedate=new \DateTime('now');
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
     * @return QVotes
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
     * Set votedate
     *
     * @param \DateTime $votedate
     *
     * @return QVotes
     */
    public function setVotedate($votedate)
    {
        $this->votedate = $votedate;

        return $this;
    }

    /**
     * Get votedate
     *
     * @return \DateTime
     */
    public function getVotedate()
    {
        return $this->votedate;
    }

    /**
     * Set idr
     *
     * @param \AppBundle\Entity\QReply $idr
     *
     * @return QVotes
     */
    public function setIdr(\AppBundle\Entity\QReply $idr = null)
    {
        $this->idr = $idr;

        return $this;
    }

    /**
     * Get idr
     *
     * @return \AppBundle\Entity\QReply
     */
    public function getIdr()
    {
        return $this->idr;
    }

    /**
     * Set idq
     *
     * @param \AppBundle\Entity\QQuestions $idq
     *
     * @return QVotes
     */
    public function setIdq(\AppBundle\Entity\QQuestions $idq = null)
    {
        $this->idq = $idq;

        return $this;
    }

    /**
     * Get idq
     *
     * @return \AppBundle\Entity\QQuestions
     */
    public function getIdq()
    {
        return $this->idq;
    }


    /**
     * Set idu
     *
     * @param \AppBundle\Entity\User $idu
     *
     * @return QVotes
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
