<?php
namespace App\Player\Infrastructure\DataFixtures;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Age;
use App\Player\Domain\ValueObject\Gender;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Strength;
use App\Player\Domain\ValueObject\Skill\Velocity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('es_AR');

        // Crear 10 jugadores masculinos
        for ($i = 0; $i < 10; $i++) {
            $player = new Player();
            $player->setName($faker->name('male'));
            try {
                $player->setGender(new Gender('male')); // Correcto: creando instancia de Gender
            } catch (\InvalidArgumentException $e) {
                error_log("Error creando género masculino en fixture: " . $e->getMessage());
            }
            $player->setStrength(new Strength(rand(0, 100)));
            $player->setVelocity(new Velocity(rand(0, 100)));
            $player->setReaction(new Reaction(rand(0, 100)));
            $player->setAge(new Age(rand(18, 40)));
            $player->setPoints(0);
            $manager->persist($player);
        }

        // Crear 10 jugadoras femeninas
        for ($i = 0; $i < 10; $i++) {
            $player = new Player();
            $player->setName($faker->name('female'));
            try {
                $player->setGender(new Gender('female')); // Correcto: creando instancia de Gender
            } catch (\InvalidArgumentException $e) {
                error_log("Error creando género femenino en fixture: " . $e->getMessage());
            }
            $player->setStrength(new Strength(rand(0, 100)));
            $player->setVelocity(new Velocity(rand(0, 100)));
            $player->setReaction(new Reaction(rand(0, 100)));
            $player->setAge(new Age(rand(18, 40)));
            $player->setPoints(0);
            $manager->persist($player);
        }

        $manager->flush();
    }
}