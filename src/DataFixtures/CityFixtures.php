<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 1000; $i++) {
            $city = new City();
            $city->setName("Lyon");
            $city->setZipCode("69001");

            $manager->persist($city);

        }

        $manager->flush();
    }
}
