<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Job;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Data scientist",
            "Statisticien",
            "Analyste cyber-securite",
            "Medecin ORL",
            "Echographiste",
            "Mathematicien",
            "Ingenieur Logiciel",
            "Analyste informatique",
            "Pathologiste du discours / Langage",
            "Actuaire",
            "Ergotherapeute",
            "Directeur des Ressources Humaines",
            "Hygieniste dentaire"
        ];

        for($i = 0; $i < count($data); $i++){
            $job = new Job();
            $job->setDesignation($data[$i]);
            $manager->persist($job);
        }

        $manager->flush();
    }
}
