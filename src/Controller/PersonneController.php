<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne.list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();

        return  $this->render('personne/index.html.twig',[
            'personnes' => $personnes
        ]);
    }

    #[Route('/all/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonneByAgeInterval($ageMin, $ageMax);

        return  $this->render('personne/index.html.twig',[
            'personnes' => $personnes
        ]);
    }

    #[Route('/stats/age/{ageMin}/{ageMax}', name: 'personne.stats.age')]
    public function statsPersonneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statsPersonneByAgeInterval($ageMin, $ageMax);

        return  $this->render('personne/stats.html.twig',[
            'stats' => $stats[0],
            'ageMin' => $ageMin,
            'ageMax' => $ageMax
        ]);
    }

    #[Route('/all/{page?1}/{nbre?12}', name: 'personne.all')]
    public function all(ManagerRegistry $doctrine, $page, $nbre): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findBy([], [], $nbre, ($page - 1) * $nbre);
        $nbrePersonne = $repository->count([]);
        $nbrePage = ceil($nbrePersonne / $nbre);


        return  $this->render('personne/index.html.twig',[
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }

    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);

        if(!$personne){
            $this->addFlash('error',"la personne n'existe pas");
            return $this->redirectToRoute('personne.list');
        }

        return  $this->render('personne/detai.html.twig',[
            'personne' => $personne
        ]);
    }

    // #[Route('/{id}', name: 'personne.detail')]
    // public function detail(Personne $personne = null): Response
    // {
    //     if(!$personne){
    //         $this->addFlash('error',"la personne n'existe pas");
    //         return $this->redirectToRoute('personne.list');
    //     }

    //     return  $this->render('personne/detai.html.twig',[
    //         'personne' => $personne
    //     ]);
    // }


    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function add(Personne $personne = null, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger
            ): Response
    {
        $new = false;

        if(!$personne){
            $new = true;
            $personne = new Personne();
        }

        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');

        // Mon formulaire va aller traiter la requet
        $form->handleRequest($request);

        // Est ce que le formulaire est soumis
        if($form->isSubmitted() && $form->isValid()){

            $imageFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('personne_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $personne->setImage($newFilename);
            }

            // Si Oui
                // On va ajouter l'objet personne dans la base de donnees
                $manager = $doctrine->getManager();

                $manager->persist($personne);

                $manager->flush();

                if($new)
                {
                    $message = " a ete ajouter avec succes" ;
                }
                else{
                    $message = " a ete mis a jour avec succes" ;
                }
                // Afficher un message de succees
                $this->addFlash('success', $personne->getFirstName(). $message);

                // On va rediriger vers la liste des personne
                return $this->redirectToRoute('personne.list');
        }
        else{
            // Sinon
                // On va afficher le formulaire
            return $this->render('personne/add-personne.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    #[Route('/delete/{id}', name: 'delete_personne')]
    public function delete(ManagerRegistry $doctrine, Personne $personne = null): Response
    {
        if($personne)
        {
            $manager = $doctrine->getManager();

            $manager->remove($personne);

            $manager->flush();

            $this->addFlash('succes','la personne a ete supprimee avec succee');

        }else{
            
            $this->addFlash('error',"la personne n'existe pas");
        }
        return $this->redirectToRoute('personne.all');
    }

    #[Route('/update/{id}/{firstName}/{lastName}/{age}', name: 'update_personne')]
    public function update(ManagerRegistry $doctrine, Personne $personne = null, $firstName, $lastName, $age): Response
    {
        if($personne)
        {
            $personne->setFirstName($firstName);
            $personne->setLastName($lastName);
            $personne->setAge($age);

            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();

            $this->addFlash('succes','la personne a ete modifier avec succee');

        }else{
            
            $this->addFlash('error',"la personne n'existe pas");
        }
        return $this->redirectToRoute('personne.all');
    }
}