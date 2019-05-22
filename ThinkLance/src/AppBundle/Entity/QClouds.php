<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QClouds
 *
 * @ORM\Table(name="clouds")
 * @ORM\Entity(repositoryClass="QandABundle\Repository\CloudsRepository")
 */
class QClouds
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
     * @ORM\Column(name="img", type="blob", nullable=true)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="blob", nullable=true)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="blob", nullable=true)
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="vocal", type="blob", nullable=true)
     */
    private $vocal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posted", type="datetime", nullable=true)
     */
    private $posted;


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
     * Set img
     *
     * @param string $img
     *
     * @return QClouds
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return QClouds
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set video
     *
     * @param string $video
     *
     * @return QClouds
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set vocal
     *
     * @param string $vocal
     *
     * @return QClouds
     */
    public function setVocal($vocal)
    {
        $this->vocal = $vocal;

        return $this;
    }

    /**
     * Get vocal
     *
     * @return string
     */
    public function getVocal()
    {
        return $this->vocal;
    }

    /**
     * Set posted
     *
     * @param \DateTime $posted
     *
     * @return QClouds
     */
    public function setPosted($posted)
    {
        $this->posted = $posted;

        return $this;
    }

    /**
     * Get posted
     *
     * @return \DateTime
     */
    public function getPosted()
    {
        return $this->posted;
    }
}
