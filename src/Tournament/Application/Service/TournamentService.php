<?php

namespace App\Tournament\Application\Service;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Domain\Entity\Tournament;

use App\Tournament\Domain\Repository\TournamentRepositoryInterface;

class TournamentService
{
    private TournamentRepositoryInterface $tournamentRepository;

    public function __construct(TournamentRepositoryInterface $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    public function createAndSaveTournament(string $gender): Tournament
    {
        $tournament = new Tournament();
        $tournament->setGenderTournament(new Gender($gender));
        $tournament->setStartDate(new \DateTimeImmutable());
        $tournament->setName("");

        $this->tournamentRepository->save($tournament);

        // Generar el nombre dinámico después de que la ID haya sido asignada
        $tournament->setName(sprintf('Master 1000 Edición %s - %s', $tournament->getId(), ucfirst($gender)));

        $this->tournamentRepository->save($tournament);

        return $tournament;
    }

    public function setTournamentWinner(Tournament $tournament, Player $winner): void
    {
        $tournament->setWinner($winner);
        $this->tournamentRepository->save($tournament);
    }

    public function save(Tournament $tournament): void
    {
        $this->tournamentRepository->save($tournament);
    }
}