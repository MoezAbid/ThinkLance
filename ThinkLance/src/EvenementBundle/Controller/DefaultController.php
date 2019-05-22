<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EvenementBundle:Default:index.html.twig');
    }

    public function mapAction()
    {
        return $this->render('EvenementBundle:Default:map.html.twig');
    }

    public function statAction()
    {
        $pieChart = new PieChart();
        $em = $this->getDoctrine();
        $Evenements = $em->getRepository(Evenement::class)->findAll();
        $total = 0;
        foreach ($Evenements as $evenement) {
            $total = $total + 1;
        }
        $data = array();
        $stat = ['classe', 'nbEtudiant'];
        $nb = 0;
        array_push($data, $stat);
        foreach ($classes as $classe) {
            $stat = array();
            array_push($stat, $classe->getNom(), (($classe->getNbEtudiants()) * 100) / $totalEtudiant);
            $nb = ($classe->getNbEtudiants() * 100) / $totalEtudiant;
            $stat = [$classe->getNom(), $nb];
            array_push($data, $stat);
        }
        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('Pourcentages des étudiants par niveau');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('@Graphe\Default\index.html.twig', array('piechart' => $pieChart));
    }

}
