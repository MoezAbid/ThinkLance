<?php

namespace QandAMobileBundle\Controller;

use AppBundle\Entity\FosUser;
use AppBundle\Entity\QClouds;
use AppBundle\Entity\QNotification;
use AppBundle\Entity\QQuestions;
use AppBundle\Entity\QReply;
use AppBundle\Entity\QViews;
use AppBundle\Entity\QVotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('QandAMobileBundle:Default:index.html.twig');
    }

    public function allQuestionsAction(){
        $questions=$this->getDoctrine()->getManager()->getRepository(QQuestions::class)->findAll();

        $serialize=new Serializer([new ObjectNormalizer()]);
        $format= $serialize->normalize($questions);
        return new JsonResponse($format);
    }

    public function questionRepliesAction(Request $idq){
        $replies=$this->getDoctrine()->getManager()->getRepository(QReply::class)->findBy(['idq'=>$idq->get('idq')]);


        $serialize=new Serializer([new ObjectNormalizer()]);
        $format= $serialize->normalize($replies);
        return new JsonResponse($format);
    }

    public function postQuestionAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $question= new QQuestions();
        $question->setQuestion($request->get('question'));
        $question->setDescription($request->get('description'));
        $question->setSubject($request->get('subject'));
        $question->setIdu($em->getRepository(FosUser::class)->find($request->get('idu')));
        $em->persist($question);
        $em->flush();

        return new JsonResponse("done");
    }

    public function deleteQuestionAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(QQuestions::class)->find($request->get('idq'));
        if($question->getIdc()!= null){
            $cloud= $em->getRepository(QClouds::class)->find($question->getIdc());
            $em->remove($cloud);
        }
        $em->remove($question);
        $em->flush();
        return new JsonResponse("done");
    }
    public function updateQuestionAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(QQuestions::class)->find($request->get('idq'));

        $question->setQuestion($request->get('question'));
        $question->setDescription($request->get('description'));
        $question->setSubject($request->get('subject'));
        $em->flush();
        return new JsonResponse("done");
    }

    public function appUserAction(Request $request){

        $serialize=new Serializer([new ObjectNormalizer()]);
        $format= $serialize->normalize($this->getDoctrine()->getRepository(FosUser::class)->find($request->get('id')));
        return new JsonResponse($format);
    }


    public function replyQuestionAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $quest=$em->getRepository(QQuestions::class)->find($request->get('idq'));

        $userId= $em->getRepository(FosUser::class)->find($request->get('idu'));
        if($quest->getIdu()!=$userId){
            $view=$em->getRepository(QViews::class)->findOneBy(['idu'=>$userId->getId(),'type'=>2,'viewIdentification'=>$quest->getId()]);
            if($view==null){
                $v= new QViews();
                $v->setType(2);
                $v->setIdu($userId);
                $v->setViewIdentification($quest->getId());
                $quest->incrementViews();
                $em->persist($v);
                $em->flush();
            }

        }

        $reply=new QReply();
        $reply->setContent($request->get('content'));
         $reply->setIdq($quest);
            $reply->setIdu($userId);
            $quest->incrementReplies();
            $em->persist($reply);
            $em->flush();
            // delete this line after testing the notification
            // =========notify the question'owner ==========
            if($userId != $quest->getIdu()) {
                $not = new QNotification();
                $not->setDeclanched(new \DateTime('now'));
                $not->setIdu($quest->getIdu());
                $not->setType(2);
                $not->setIdtype($reply->getId());

                $em->persist($not);
            }
            // ===============                       ==========
        return new JsonResponse($reply);
    }

    public function upVoteAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $reply=$this->getDoctrine()->getRepository(QReply::class)->find($request->get('idr'));
        $hasvoted=$this->getDoctrine()->getRepository(QVotes::class)->findOneBy(['idr' => $request->get('idr'),'idu'=>$request->get('idu')]);

        if($hasvoted==null)
        {
            $vote=new QVotes();
            $em->getRepository('AppBundle:QReply')-> upVote($request->get('idr'));
            $vote->setType(1);
            $vote->setIdu($em->getRepository(FosUser::class)->find($request->get('idu')));
            $vote->setIdr($reply);
            $reply->upScore();
            $em->persist($vote);
            $em->flush();
        }
        else {
            if($hasvoted->getType()==2){
                $hasvoted->setType(1);
                $reply->upScore();
                $em->flush();
            }
        }
        return new JsonResponse($reply->getScore());
    }
    public function downVoteAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $reply=$this->getDoctrine()->getRepository(QReply::class)->find($request->get('idr'));
        $hasvoted=$this->getDoctrine()->getRepository(QVotes::class)->findOneBy(['idr' => $request->get('idr'),'idu'=>$request->get('idu')]);

        if($hasvoted==null)
        {
            $vote=new QVotes();
            $em->getRepository('AppBundle:QReply')-> downVote($request->get('idr'));
            $vote->setType(2);
            $vote->setIdu($em->getRepository(FosUser::class)->find($request->get('idu')));
            $vote->setIdr($reply);
            $em->persist($vote);
            $reply->downScore();
            $em->flush();
        }
        else {
            if($hasvoted->getType()==1){
                $hasvoted->setType(2);
                $reply->downScore();
                $em->flush();
            }
        }
        return new JsonResponse($reply->getScore());

    }
    public function signalQuestionAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(FosUser::class)->find($request->get('idu'));
        $quest=$em->getRepository(QQuestions::class)->find($request->get('idr'));
        $hasSignaled=$this->getDoctrine()->getRepository(QVotes::class)->findOneBy(['idq' => $quest->getId(),'type'=>4,'idu'=>$user->getId()]);
        if($hasSignaled==null)
        {
            $vote=new QVotes();
            $vote->setType(4);
            $vote->setIdu($user);
            $vote->setIdq($quest);
            $quest->incrementSignal();
            $em->persist($vote);
            $em->persist($quest);
            $em->flush();
        }
        return new JsonResponse("done");
    }
public function satisfiedReply(Request $request){
    $em=$this->getDoctrine()->getManager();
    $reply=$em->getRepository(QReply::class)->find($request->get('idr'));
    $reply->setTheAnswer(new \DateTime('now'));
    $reply->getIdq()->setAnswered(new \DateTime('now'));
    $em->flush();


    return new JsonResponse("done");
}

}
