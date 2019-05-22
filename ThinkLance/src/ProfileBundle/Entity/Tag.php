<?php

namespace ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="ProfileBundle\Repository\TagRepository")
 */
class Tag
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
     * @ORM\Column(name="nom_tag", type="string", length=255)
     */
    private $nomTag;


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
     * Set nomTag
     *
     * @param string $nomTag
     *
     * @return Tag
     */
    public function setNomTag($nomTag)
    {
        $this->nomTag = $nomTag;

        return $this;
    }

    /**
     * Get nomTag
     *
     * @return string
     */
    public function getNomTag()
    {
        return $this->nomTag;
    }
    public function __toString()
    {
        return $this->getNomTag();
    }
}

