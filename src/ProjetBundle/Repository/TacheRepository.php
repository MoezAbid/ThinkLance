<?php

namespace ProjetBundle\Repository;

/**
 * tacheRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TacheRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByIdProjet($idp)
    { $dqlresult=$this->getEntityManager()->createQuery("SELECT V FROM ProjetBundle:Tache V WHERE V.projet=:idP")
        ->setParameter('idP',$idp);

        return $dqlresult->getResult();
    }
}
