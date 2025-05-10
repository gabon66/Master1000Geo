<?php

namespace App\Tournament\Application\Service;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Domain\Entity\Tournament;

use Doctrine\ORM\EntityManagerInterface;

class TournamentService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createAndSaveTournament(string $gender): Tournament
    {
        $tournament = new Tournament();
        $tournament->setGenderTournament(new Gender($gender));
        $tournament->setStartDate(new \DateTimeImmutable());
        $tournament->setName("");


        $this->entityManager->persist($tournament);
        $this->entityManager->flush();

        // Generar el nombre dinámico después de que la ID haya sido asignada
        $tournament->setName(sprintf('Master 1000 Edición %s - %s', $tournament->getId(), ucfirst($gender)));

        $this->entityManager->persist($tournament);
        $this->entityManager->flush();

        return $tournament;
    }

    public function setTournamentWinner(Tournament $tournament, Player $winner): void
    {
        $tournament->setWinner($winner);
        $this->entityManager->persist($tournament);
        $this->entityManager->flush();
    }

    public function save(Tournament $tournament): void
    {
        $this->entityManager->persist($tournament);
        $this->entityManager->flush();
    }
}