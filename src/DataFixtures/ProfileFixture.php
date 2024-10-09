<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profile;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setUrl('https://www.facebook.com/ayyoub.bellaari');
        $profile->setRS('facebook');

        $profile1 = new Profile();
        $profile1->setUrl('https://www.twitter.com/ayyoub.bellaari');
        $profile1->setRS('twitter');

        $profile2 = new Profile();
        $profile2->setUrl('https://www.instagrame.com/ayyoub.bellaari');
        $profile2->setRS('instagrame');

        $profile3 = new Profile();
        $profile3->setUrl('https://www.github.com/ayyoub.bellaari');
        $profile3->setRS('gitub');

        $manager->persist($profile);
        $manager->persist($profile1);
        $manager->persist($profile2);
        $manager->persist($profile3);

        $manager->flush();
    }
}
