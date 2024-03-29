<?php

namespace PaiementBundle\Repository;

/**
 * PaiementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaiementRepository extends \Doctrine\ORM\EntityRepository
{
    public function mesPaiementsFreelancer($currentUserId)
    {
        $dqlresult = $this->getEntityManager()->createQuery("SELECT p FROM PaiementBundle:Paiement p WHERE p.freelancer=:idFree")
            ->setParameter('idFree', $currentUserId);
        return $dqlresult->getResult();
    }

    public function mesPaiementsEmployeur($currentUserId)
    {
        $dqlresult = $this->getEntityManager()->createQuery("SELECT p FROM PaiementBundle:Paiement p WHERE p.employeur=:idEmp")
            ->setParameter('idEmp', $currentUserId);
        return $dqlresult->getResult();
    }

    public function nombreDePaiementSelonLaClasseDeLemployeur($classe)
    {
        $dqlresult = null;
        if ($classe == "Bronze") {
            $dqlresult = $this->getEntityManager()->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.employeur e WHERE e.nbrMission < 10");
        } else if ($classe == "Silver") {
            $dqlresult = $this->getEntityManager()->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.employeur e WHERE e.nbrMission BETWEEN 10 AND 20");
        } else if ($classe == "Gold") {
            $dqlresult = $this->getEntityManager()->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.employeur e WHERE e.nbrMission > 30");
        }

        return $dqlresult->getSingleScalarResult();
    }

    public function nombreDePaiementSelonLaClasseDuFreelancer($classe)
    {
        $dqlresult = null;
        if ($classe == "Bronze") {
            $dqlresult = $this->getEntityManager()->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.freelancer f WHERE f.nbrPoints < 100");
        } else if ($classe == "Silver") {
            $dqlresult = $this->getEntityManager()->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.freelancer f WHERE f.nbrPoints BETWEEN 100 AND 299");
        } else if ($classe == "Gold") {
            $dqlresult = $this->getEntityManager()->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.freelancer f WHERE f.nbrPoints >= 301");
        }

        return $dqlresult->getSingleScalarResult();
    }

    public function montantPaiementsParmois($mois)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT SUM(p.montant) FROM PaiementBundle:Paiement p WHERE MONTH(p.dateHeurePaiement)=:mois")
            ->setParameter('mois', $mois);
        return floatval($dqlresult->getSingleScalarResult());
    }

    public function nombrePaiementsParmois($mois)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p WHERE MONTH(p.dateHeurePaiement)=:mois")
            ->setParameter('mois', $mois);
        return floatval($dqlresult->getSingleScalarResult());
    }

    public function freelancerMonMontantPaiementsParmois($mois, $id)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.freelancer f WHERE MONTH(p.dateHeurePaiement)=:mois AND f.id=:id")
            ->setParameter('mois', $mois)
            ->setParameter('id', $id);
        return floatval($dqlresult->getSingleScalarResult());
    }

    public function employeurMonMontantPaiementsParmois($mois, $id)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.employeur e WHERE MONTH(p.dateHeurePaiement)=:mois AND e.id=:id")
            ->setParameter('mois', $mois)
            ->setParameter('id', $id);
        return floatval($dqlresult->getSingleScalarResult());
    }


    public function freelancerMonMombrePaiementsParmois($mois, $id)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.freelancer f WHERE MONTH(p.dateHeurePaiement)=:mois AND f.id=:id")
            ->setParameter('mois', $mois)
            ->setParameter('id', $id);
        return floatval($dqlresult->getSingleScalarResult());
    }

    public function employeurMonMombrePaiementsParmois($mois, $id)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT COUNT(p.idPaiement) FROM PaiementBundle:Paiement p JOIN p.employeur e  WHERE MONTH(p.dateHeurePaiement)=:mois AND e.id=:id")
            ->setParameter('mois', $mois)
            ->setParameter('id', $id);
        return floatval($dqlresult->getSingleScalarResult());
    }

    public function nombreDeProjetsParTypeAdmin($type)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT COUNT(p.idPaiement) FROM ProjetBundle:Projet p WHERE p.type=:type")
            ->setParameter('type', $type);
        return floatval($dqlresult->getSingleScalarResult());
    }

    public function nombreDeProjetsParTypeFreelancer($type, $id)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT COUNT(p.idPaiement) FROM ProjetBundle:Projet p WHERE p.type=:type")
            ->setParameter('type', $type);
        return floatval($dqlresult->getSingleScalarResult());
    }

    /*public function nombreDeProjetsParTypeEmployeur($type, $id)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT COUNT(p.idPaiement) FROM ProjetBundle:Projet p WHERE p.type=:type")
            ->setParameter('type', $type);
        return floatval($dqlresult->getSingleScalarResult());
    }*/

    public function rechercheParIdPaiementAdmin($idPaiement)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT p, e , f, proj FROM PaiementBundle:Paiement p JOIN p.employeur e JOIN p.freelancer f JOIN p.projet proj WHERE p.idPaiement LIKE :idP")
            ->setParameter('idP', '%' . $idPaiement . '%');
        return $dqlresult->getArrayResult();
    }

    public function rechercheParEmployeurPaiementAdmin($usernameEmployeur)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT p, e , f, proj FROM PaiementBundle:Paiement p JOIN p.employeur e JOIN p.freelancer f JOIN p.projet proj WHERE e.username LIKE :usernameEmployeur")
            ->setParameter('usernameEmployeur', '%' . $usernameEmployeur . '%');
        return $dqlresult->getArrayResult();
    }

    public function rechercheParFreelancerPaiementAdmin($usernameFreelancer)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT p, e , f, proj FROM PaiementBundle:Paiement p JOIN p.employeur e JOIN p.freelancer f JOIN p.projet proj WHERE f.username LIKE :usernameFreelancer")
            ->setParameter('usernameFreelancer', '%' . $usernameFreelancer . '%');
        return $dqlresult->getArrayResult();
    }

    public function rechercheParProjetPaiementAdmin($nomProjet)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT p, e , f, proj FROM PaiementBundle:Paiement p JOIN p.employeur e JOIN p.freelancer f JOIN p.projet proj WHERE proj.titreProjet LIKE :nomProjet")
            ->setParameter('nomProjet', '%' . $nomProjet . '%');
        return $dqlresult->getArrayResult();
    }

    public function getMaxPaiement()
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT MAX(p.montant) proj FROM PaiementBundle:Paiement");
        return $dqlresult->getSingleScalarResult();
    }

    public function rechercheParMontantPaiementAdmin($montantMax)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT p, e , f, proj FROM PaiementBundle:Paiement p JOIN p.employeur e JOIN p.freelancer f JOIN p.projet proj WHERE p.montant<=:montantMax")
            ->setParameter('montantMax', $montantMax);
        return $dqlresult->getArrayResult();
    }

    public function rechercheParDatePaiementAdmin($dateDebut)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT p, e , f, proj FROM PaiementBundle:Paiement p JOIN p.employeur e JOIN p.freelancer f JOIN p.projet proj WHERE p.dateHeurePaiement>=:dateDebut")
            ->setParameter('dateDebut', $dateDebut);
        return $dqlresult->getArrayResult();
    }

    public function rechercheParIdPaiementEmployeur($idEmployeur, $idMotCle)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT p, e , f, proj FROM PaiementBundle:Paiement p JOIN p.employeur e JOIN p.freelancer f JOIN p.projet proj WHERE p.idPaiement LIKE :idMotCle AND p.employeur=:idEmployeur")
            ->setParameter('idMotCle', '%' . $idMotCle . '%')
            ->setParameter('idEmployeur', $idEmployeur);
        return $dqlresult->getArrayResult();
    }

    public function rechercheParIdPaiementFreelancer($idFreelancer, $idMotCle)
    {
        $dqlresult = $this
            ->getEntityManager()
            ->createQuery("SELECT p, e , f, proj FROM PaiementBundle:Paiement p JOIN p.employeur e JOIN p.freelancer f JOIN p.projet proj WHERE p.idPaiement LIKE :idMotCle AND p.freelancer=:idFreelancer")
            ->setParameter('idMotCle', '%' . $idMotCle . '%')
            ->setParameter('idFreelancer', $idFreelancer);
        return $dqlresult->getArrayResult();
    }

}