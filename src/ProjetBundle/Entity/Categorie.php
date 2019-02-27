<?php

namespace ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Catégorie
 *
 * @ORM\Table(name="categorieprojet")
 * @ORM\Entity(repositoryClass="ProjetBundle\Repository\CatégorieRepository")
 */
class Categorie
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
     * @ORM\Column(name="titreCategorie", type="string", length=255)
     */
    private $titreCategorie;


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
     * Set titreCategorie
     *
     * @param string $titreCategorie
     *
     * @return Categorie
     */
    public function setTitreCategorie($titreCategorie)
    {
        $this->titreCategorie = $titreCategorie;

        return $this;
    }

    /**
     * Get titreCategorie
     *
     * @return string
     */
    public function getTitreCategorie()
    {
        return $this->titreCategorie;
    }
}

