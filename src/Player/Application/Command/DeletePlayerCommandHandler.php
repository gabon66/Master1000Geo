<?php

namespace App\Player\Application\Command;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
class DeletePlayerCommandHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PlayerRepositoryInterface $playerRepository, EntityManagerInterface $entityManager)
    {
        $this->playerRepository = $playerRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(DeletePlayerCommand $command): void
    {
        $playerId = $command->getPlayerId();
        $player = $this->playerRepository->find($playerId);

        if (!$player instanceof Player) {
            throw new NotFoundHttpException(sprintf('Player with ID "%s" not found.', $playerId));
        }

        $this->entityManager->remove($player);
        $this->entityManager->flush();
    }
}