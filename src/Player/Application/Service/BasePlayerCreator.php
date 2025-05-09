<?php

namespace App\Player\Application\Service;

use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Age;
use App\Player\Domain\ValueObject\Gender;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Strength;
use App\Player\Domain\ValueObject\Skill\Velocity;

abstract class BasePlayerCreator implements PlayerCreatorInterface
{
    protected function createBasePlayer(CreatePlayerCommand $command): Player
    {
        $gender = null;
        try {
            $gender = new Gender($command->gender);
            $age = new Age($command->age);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException('Invalid player gender: ' . $e->getMessage());
        }

        $player = new Player();
        $player->setName($command->name);
        $player->setAge($age);
        $player->setGender($gender);
        return $player;
    }

    protected function createStrength(int $value): Strength
    {
        return new Strength($value);
    }

    protected function createVelocity(int $value): Velocity
    {
        return new Velocity($value);
    }

    protected function createReaction(int $value): Reaction
    {
        return new Reaction($value);
    }
}