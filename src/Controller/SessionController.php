<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if($session->has('nbVisit')){
            $nbrVisit = $session->get('nbVisit') + 1;
        }else{
            $nbrVisit = 1;
        }
        $session->set('nbVisit',$nbrVisit);
        return $this->render('session/index.html.twig');
    }
}
