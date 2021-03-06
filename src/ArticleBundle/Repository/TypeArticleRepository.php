<?php

namespace ArticleBundle\Repository;

/**
 * TypeArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TypeArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function trouverTypeArticleParNom($nomTypeRecherche)
    {
        $dqlresult = $this->getEntityManager()->createQuery("SELECT t FROM ArticleBundle:TypeArticle t WHERE t.nom=:type")
            ->setParameter('type', $nomTypeRecherche);
        return $dqlresult->getResult();
    }
}
