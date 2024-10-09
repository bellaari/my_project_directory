<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Hobby;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Yoga",
            "Cuisine",
            "Patisserie",
            "Photographie",
            "Blogging",
            "Lecture",
            "Apprendre une langue",
            "Construction lego",
            "Dessin",
            "peinture",
            "Se lancer dans le tissage de tapis",
            "Crees des vetements ou des cosplay",
            "Fabriquer des bijoux",
            "Travailler le metal",
            "Decorer des galets",
            "Ameliorer son espace de vie",
            "Apprendre a jongler",
            "Faire partie d'un club de lecture",
            "Apprendre la programmation informatique"
        ];
        
        for($i = 0; $i < count($data); $i++){
            $hobby = new Hobby();
            $hobby->setDesignation($data[$i]);
            $manager->persist($hobby);
        }

        $manager->flush();
    }
}
