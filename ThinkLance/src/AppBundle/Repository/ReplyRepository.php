<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 2019-03-20
 * Time: 14:36
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReplyRepository extends EntityRepository
{
    public function responses($idq){
    $query=$this->getEntityManager()->createQuery("select r,v from AppBundle:QReply r , AppBundle:QVotes v where r.id=v.idr AND r.idq='$idq' ");
    return $query->getResult();
}

    public function getTopReplies($id){
        $query=$this->getEntityManager()->createQuery("SELECT r FROM AppBundle:QReply r WHERE r.idu='$id' ORDER BY r.score DESC");
        return $query->getResult();
    }

public function getNotVoted(){
    $query=$this->getEntityManager()->createQuery("SELECT r FROM AppBundle:QReply r WHERE r.id not in (SELECT (v.idr) FROM AppBundle:QVotes v) ");
    return $query->getResult();
}

public function upVote($idr){
    $qb = $this->createQueryBuilder('reply')
        ->update()
        ->set('reply.score', 'reply.score  + 1')
        ->where('reply.id=' . $idr);

    return $qb->getQuery()
        ->getResult();

}
    public function downVote($idr){
        $qb = $this->createQueryBuilder('reply')
            ->update()
            ->set('reply.score', 'reply.score  - 1')
            ->where('reply.id=' . $idr);

        return $qb->getQuery()
            ->getResult();

    }

    public function countScore($idr){
        $up=$this->getEntityManager()->createQuery("select COUNT(v.idu) from AppBundle:QVotes v where v.type=1 AND v.idr='$idr' ");
        $down=$this->getEntityManager()->createQuery("select COUNT(v.idu) from AppBundle:QVotes v where v.type=2 AND v.idr='$idr' ");
        return ($up->getResult() - $down->getResult());
    }

    public function getNBRReplies($id){
        $qb=$this->getEntityManager()->createQueryBuilder();
        $qb->select('count(r)');
        $qb->from('AppBundle:QReply','r');
        $qb->where($qb->expr()->eq('r.idu',$id));
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function notifReplies($idu){
        $query=$this->getEntityManager()->createQuery("SELECT r FROM AppBundle:QReply r,AppBundle:QNotification n WHERE r.id=n.idtype AND n.type=2 AND n.idu='$idu' ORDER BY r.declanched DESC");
        return $query->getResult();

    }
    public function repliesNotViewed($idq){
        $query=$this->getEntityManager()->createQuery("SELECT r FROM AppBundle:QReply r,AppBundle:QNotification n WHERE r.idq='$idq' AND r.id=n.idtype AND n.type=2 ORDER BY r.score DESC");
        return $query->getResult();

    }
}