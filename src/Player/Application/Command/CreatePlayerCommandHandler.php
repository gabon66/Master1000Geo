<?php

namespace App\Player\Application\Command;

use App\Player\Application\Service\FemalePlayerCreator;
use App\Player\Application\Service\MalePlayerCreator;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreatePlayerCommandHandler
{
    private PlayerRepositoryInterface $playerRepository;
    public function __construct(PlayerRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function __invoke(CreatePlayerCommand $command): Player
    {
        $gender = strtolower(trim($command->gender));
        $playerCreator = $gender === 'male' ? new MalePlayerCreator() : new FemalePlayerCreator();
        $player = $playerCreator->create($command);
        $this->playerRepository->save($player);

        return $player;
    }
}