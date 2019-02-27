<?php

namespace ReclamationBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categoriereclamation")
 * @ORM\Entity(repositoryClass="ReclamationBundle\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomC", type="string", length=255)
     * @Assert\NotBlank()
     *  * @Assert\Length(
     *      min = 3,
     *      max = 21,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $nomC;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionC", type="string", length=255)
     * @Assert\NotBlank()
     *  * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $descriptionC;


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
     * Set nomC
     *
     * @param string $nomC
     *
     * @return Categorie
     */
    public function setNomC($nomC)
    {
        $this->nomC = $nomC;

        return $this;
    }

    /**
     * Get nomC
     *
     * @return string
     */
    public function getNomC()
    {
        return $this->nomC;
    }

    /**
     * Set descriptionC
     *
     * @param string $descriptionC
     *
     * @return Categorie
     */
    public function setDescriptionC($descriptionC)
    {
        $this->descriptionC = $descriptionC;

        return $this;
    }

    /**
     * Get descriptionC
     *
     * @return string
     */
    public function getDescriptionC()
    {
        return $this->descriptionC;
    }
}

