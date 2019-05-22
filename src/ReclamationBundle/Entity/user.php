<?php

namespace ReclamationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * user
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ReclamationBundle\Repository\userRepository")
 */
class user
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
     * @ORM\Column(name="nomu", type="string", length=255)
     */
    private $nomu;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd", type="string", length=255)
     */
    private $pwd;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;




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
     * Set id
     *
     * @param integer $id
     *
     * @return user
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set nomu
     *
     * @param string $nomu
     *
     * @return user
     */
    public function setNomu($nomu)
    {
        $this->nomu = $nomu;

        return $this;
    }

    /**
     * Get nomu
     *
     * @return string
     */
    public function getNomu()
    {
        return $this->nomu;
    }

    /**
     * Set pwd
     *
     * @param string $pwd
     *
     * @return user
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;

        return $this;
    }

    /**
     * Get pwd
     *
     * @return string
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return user
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

