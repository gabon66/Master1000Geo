<?php

namespace App\Player\Application\Service;

use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Domain\Entity\Player;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService(implements: PlayerCreatorInterface::class)]
#[AsService(tag: 'player.creator', priority: 0)] // Tag para la selección
class MalePlayerCreator extends BasePlayerCreator
{
    public function create(CreatePlayerCommand $command): Player
    {
        $player = $this->createBasePlayer($command);

        if ($command->strength === null) {
            throw new \InvalidArgumentException('Strength is required for male players.');
        }
        $player->setStrength($this->createStrength($command->strength));

        if ($command->velocity === null) {
            throw new \InvalidArgumentException('Velocity is required for male players.');
        }
        $player->setVelocity($this->createVelocity($command->velocity));

        $player->setReaction($command->reaction !== null ? $this->createReaction($command->reaction) : null);

        return $player;
    }
}