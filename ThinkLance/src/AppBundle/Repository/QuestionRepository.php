<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 2019-03-20
 * Time: 14:35
 */

namespace AppBundle\Repository;


use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNewestQuestions(){
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q ORDER BY q.postdate DESC");
        return $query->getResult();
    }

    public function getNBRQuestions($id){
        $qb=$this->getEntityManager()->createQueryBuilder();
        $qb->select('count(q)');
        $qb->from('AppBundle:QQuestions','q');
        $qb->where($qb->expr()->eq('q.idu',$id));
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function anwserQuestion($idq){

    $now=new \DateTime('now');
      $query=$this->getEntityManager()->createQuery("UPDATE AppBundle:QQuestions q SET q.answered='$now' WHERE id='$idq' ");
    }
public function isFoundQuestion($q, $d){
$query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.question='$q' OR q.description='$d'");
return $query->getResult();
    }

    public function getMyQuestions(){
        $user=$this->container->get('security.token_storage')->getToken()->getUser();
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.idu.id='$user' ");
        return $query->getResult();
    }

    public function getUserQuestions($idu){
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.idu='$idu' ");
        return $query->getResult();
    }

    public function getNAUserQuestions($idu){
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.answered IS null AND q.idu='$idu' ");
        return $query->getResult();
    }
    public function getAUserQuestions($idu){
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.answered IS NOT null AND q.idu='$idu' ");
        return $query->getResult();
    }
    public function getNotRepliedQuestion($idu){
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.idu='$idu' AND q.id not in (SELECT (r.idq) FROM AppBundle:QReply r)");
        return $query->getResult();
    }

public function getAnseweredQuestions(){
    $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.answered is NOT null ORDER BY q.postdate DESC");
        return $query->getResult();
}
    public function getNotAnseweredQuestions(){
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.answered is null ORDER BY q.postdate DESC");
        return $query->getResult();
    }

public function getAnseweredQuestionsBySubject($sub){
    $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.answered IS NOT null AND q.subject='$sub' ORDER BY q.postdate DESC");
        return $query->getResult();
}
    public function getNotAnseweredQuestionsBySubject($sub){
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.answered IS null AND q.subject='$sub' ORDER BY q.postdate DESC");
        return $query->getResult();
    }
public function getAQBySubject($user, $sub){
    $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.answered IS NOT null AND q.idu='$user' AND q.subject='$sub' ORDER BY q.postdate DESC");
    return $query->getResult();
}
    public function getNotAQBySubject($user, $sub){
        $query=$this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.answered IS null AND q.idu='$user' AND q.subject='$sub' ORDER BY q.postdate DESC");
        return $query->getResult();
    }

public function getQuestionsBySubject($sub){
    $query = $this->getEntityManager()->createQuery("SELECT q FROM AppBundle:QQuestions q WHERE q.subject='$sub' ORDER BY q.postdate DESC");
    return $query->getResult();
}

public function getSignaled($page = 1, $max = 4){

    $qb=$this->getEntityManager()->createQueryBuilder();
    $qb->select('q');
    $qb->from('AppBundle:QQuestions','q');
    $qb->where($qb->expr()->gte('q.signaler',6));

    $firstResult = ($page - 1) * $max;

    $query = $qb->getQuery();
    $query->setFirstResult($firstResult);
    $query->setMaxResults($max);

    $paginator = new Paginator($query);

    if(($paginator->count() <=  $firstResult) && $page != 1) {
        throw new NotFoundHttpException('Page not found');
    }

    return $paginator;

}

}