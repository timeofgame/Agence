<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Property;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        for($i=0; $i<100; $i++){
           $property = new Property();
           $property->setTitle($faker->words(3, true))
                    ->setDescription($faker->sentences(3, true))
                    ->setSurface($faker->numberBetween(20, 350))
                    ->setRooms($faker->numberBetween(2, 10))
                    ->setBedrooms($faker->numberBetween(1, 10))
                    ->setFloor($faker->numberBetween(0,10))
                    ->setPrice($faker->numberBetween(100000, 1000000))
                    ->setHeat($faker->numberBetween(0, count(Property::HEAT)-1))
                    ->setCity($faker->city)
                    ->setAddress($faker->address)
                    ->setPostalCode($faker->postcode)
                    ->setSold(false)
                    ;
            $manager->persist($property);
        }
        $manager->flush();
    }
}