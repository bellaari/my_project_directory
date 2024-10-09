<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/first/ibrahim', name: 'app_first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig',[
            'parent' => 'Ibrahim',
            'child' => 'Ayoub'
        ]);
    }

    #[Route('/sayHello/{name?Ibrahim}/{lastname?BELLAARI}', name: 'say.hello')]
    public function sayHello(Request $request,$name,$lastname): Response
    {
        return $this->render('first/hello.html.twig',[
            'prenom' => $name,
            'nom' => $lastname,
            'path' => ''
        ]);
    }

    #[Route(
        '/multi/{entier1<\d+>}/{entier2<\d+>}', 
        name: 'multi',
        //requirements : ['entier1' => '\d+' , 'entier2' => '\d+']
        )]
    public function multiplication($entier1,$entier2): Response
    {
        $resultat = $entier1 * $entier2;
        return new Response($resultat);
    }
}
