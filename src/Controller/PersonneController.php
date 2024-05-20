<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne.list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();

        return  $this->render('personne/index.html.twig',['personnes' => $personnes]);
    }

    #[Route('/{id}', name: 'personne.detail')]
    public function detail(Personne $personne = null): Response
    {
        if(!$personne){
            $this->addFlash('error',"la personne n'existe pas");
            return $this->redirectToRoute('personne.list');
        }

        return  $this->render('personne/detai.html.twig',['personne' => $personne]);
    }


    #[Route('/add', name: 'app_personne')]
    public function add(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setFirstName('Ibrahim');
        $personne->setLastName('Bellaari');
        $personne->setAge('63');

        $personne1 = new Personne();
        $personne1->setFirstName('Ayyoub');
        $personne1->setLastName('Bellaari');
        $personne1->setAge('27');

        $personne2 = new Personne();
        $personne2->setFirstName('Amin');
        $personne2->setLastName('Bellaari');
        $personne2->setAge('25');

        $entityManager->persist($personne2);

        $entityManager->flush();


        return $this->render('personne/detai.html.twig', [
            'personne' => $personne2,
        ]);
    }
}
