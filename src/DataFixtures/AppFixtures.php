<?php

namespace App\DataFixtures;

use App\Domain\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('es_AR');

        for ($i = 0; $i < 20; $i++) { // Aumentamos la cantidad de jugadores
            $player = new Player();
            $player->setName($faker->name());
            $player->setGender($faker->boolean());
            $player->setStrength(rand(1, 100));
            $player->setVelocity(rand(1, 100));
            $player->setReaction(rand(1, 100));
            $player->setAge(rand(18, 40));
            $player->setPoints(0);
            $manager->persist($player);
        }

        $manager->flush();
    }
}