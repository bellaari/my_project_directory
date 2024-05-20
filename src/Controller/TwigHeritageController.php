<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwigHeritageController extends AbstractController
{
    #[Route('/twig/heritage', name: 'app_twig_heritage')]
    public function index(): Response
    {
        return $this->render('twig_heritage/index.html.twig', [
            'controller_name' => 'TwigHeritageController',
        ]);
    }

    #[Route('/heritage', name: 'app_twig_heritage.heritage')]
    public function heritage(): Response
    {
        return $this->render('heritage.html.twig');
    }

    #[Route('/template', name: 'app_template')]
    public function template(): Response
    {
        return $this->render('template.html.twig');
    }
}
