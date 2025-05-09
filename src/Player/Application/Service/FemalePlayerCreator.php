<?php

namespace App\Player\Application\Service;

use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Domain\Entity\Player;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService(implements: PlayerCreatorInterface::class)]
#[AsService(tag: 'player.creator', priority: 0)] // Tag para la selección
class FemalePlayerCreator extends BasePlayerCreator
{
    public function create(CreatePlayerCommand $command): Player
    {
        $player = $this->createBasePlayer($command);

        if ($command->reaction === null) {
            throw new \InvalidArgumentException('Reaction is required for female players.');
        }
        $player->setReaction($this->createReaction($command->reaction));

        $player->setStrength($command->strength !== null ? $this->createStrength($command->strength) : null);
        $player->setVelocity($command->velocity !== null ? $this->createVelocity($command->velocity) : null);

        return $player;
    }
}