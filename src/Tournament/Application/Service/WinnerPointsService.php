<?php

namespace App\Tournament\Application\Service;

use App\Player\Domain\Entity\Player;
use App\Tournament\Domain\Enum\TournamentRules;
use App\Tournament\Domain\Service\WinnerPointsServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Servicio para contabilizar los puntos por cada partido ganado
 */
class WinnerPointsService implements WinnerPointsServiceInterface
{
    private EntityManagerInterface $entityManager;
    private int $pointsToAdd;

    public function __construct(EntityManagerInterface $entityManager,
                                int $pointsToAdd = TournamentRules::MATCH_WIN_POINT->value
    )
    {
        $this->entityManager = $entityManager;
        $this->pointsToAdd = $pointsToAdd;
    }

    public function addPoints(Player $winner): void
    {
        $currentPoints = $winner->getPoints();
        $winner->setPoints($currentPoints + $this->pointsToAdd);
        $this->entityManager->flush();
    }
}