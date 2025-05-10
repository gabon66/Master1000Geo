<?php

namespace App\Tournament\Application\Service;

use App\Player\Domain\Entity\Player;
use App\Tournament\Domain\Entity\MatchGame;
use App\Tournament\Domain\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;

class MatchService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveMatch(Player $player1, ?Player $player2, Player $winner, Tournament $tournamentId): void
    {
        $match = new MatchGame();
        $match->setPlayer1($player1);
        $match->setPlayer2($player2);
        $match->setPlayerWin($winner);
        $match->setStartDate(new \DateTime());
        $match->setEndDate(new \DateTime());
        $match->setTournament($tournamentId);

        $this->entityManager->persist($match);
        $this->entityManager->flush();
    }
}