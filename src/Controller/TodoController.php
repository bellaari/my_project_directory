<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if(!$session->has('todo')){
            $todos = [
                'Achat'=>'Acheter des articles',
                'Cour'=>'Finaliser mes cours',
                'Correction'=>'Coriger mes examens'
            ];
            $session->set('todo',$todos);
        }
        $this->addFlash('info',"la liste des todos viens d'etre initialise");
        return $this->render('todo/index.html.twig');
    }
    #[Route(
        '/add/{cle?Ibrahim}/{element?the parent of Ayyoub, Amin, Rajae et Ouafae}',
        name: 'app_todo.add',
        defaults: ['cle' => 'Ibrahim', 'element' => 'the parent of Ayyoub, Amin, Rajae et Ouafae']
    )]
    public function addTodo(Request $request, $cle, $element): RedirectResponse
    {
        //verifier si le tableau des todos existe dans la session
        $session = $request->getSession();
        if($session->has('todo')){

            //verifier si le nom de todo existe dans le tableau des todos

            $todos = $session->get('todo');
            if(isset($todos[$cle])){
                $this->addFlash('error',"le todo d'id $cle existe deja");
            }else{
                //si le nom n'existe pas

                $todos[$cle]=$element;
                $session->set('todo',$todos);
                $this->addFlash('success',"le todo ajout  avec success");

            }
        } else {

            //si nom affiche an message d'erreur et on va rediriger vers le controller index
            $this->addFlash('error',"le le tableau des todos n'existe pas dans la session");
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/delete/{cle}', name: 'app_todo.delete')]
    public function supTodo(Request $request, $cle): RedirectResponse
    {
        //verifier si le tableau des todos existe dans la session
        $session = $request->getSession();
        if($session->has('todo')){

            //verifier si le nom de todo existe dans le tableau des todos

            $todos = $session->get('todo');
            if(isset($todos[$cle])){
                unset($todos[$cle]);
                $session->set('todo',$todos);
                $this->addFlash('success',"le todo a ete supprime avec success");

            }else{
                //si le nom n'existe pas
                $this->addFlash('error',"le todo d'id $cle n'existe pas");
            }
        } else {

            //si nom affiche an message d'erreur et on va rediriger vers le controller index
            $this->addFlash('error',"le le tableau des todos n'existe pas dans la session");
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/update/{cle}/{element}', name: 'app_todo.update')]
    public function updateTodo(Request $request, $cle, $element): RedirectResponse
    {
        //verifier si le tableau des todos existe dans la session
        $session = $request->getSession();
        if($session->has('todo')){

            //verifier si le nom de todo existe dans le tableau des todos

            $todos = $session->get('todo');
            if(isset($todos[$cle])){
                $todos[$cle]=$element;
                $session->set('todo',$todos);
                $this->addFlash('success',"le todo a ete modifie avec success");

            }else{
                //si le nom n'existe pas
                $this->addFlash('error',"le todo d'id $cle n'existe pas");
            }
        } else {

            //si nom affiche an message d'erreur et on va rediriger vers le controller index
            $this->addFlash('error',"le le tableau des todos n'existe pas dans la session");
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/reset', name: 'app_todo.reset')]
    public function resetTodo(Request $request): RedirectResponse
    {
        $session = $request->getSession();
        $session->remove('todo');

        return $this->redirectToRoute('app_todo');
    }
}
