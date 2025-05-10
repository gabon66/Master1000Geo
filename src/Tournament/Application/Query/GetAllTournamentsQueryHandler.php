<?php

namespace App\Tournament\Application\Query;

use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Domain\Entity\Tournament;
use App\Tournament\Domain\Repository\TournamentRepositoryInterface;

class GetAllTournamentsQueryHandler
{
    private TournamentRepositoryInterface $tournamentRepository;

    public function __construct(TournamentRepositoryInterface $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    /**
     * @return Tournament[]
     */
    public function __invoke(GetAllTournamentsQuery $query): array
    {
        $criteria = [];
        if ($query->getGender()) {
            $gender = new Gender($query->getGender());
            $criteria['genderTournament'] = $gender->getValue();
        }

        return $this->tournamentRepository->findBy($criteria, ['startDate' => 'DESC']);
    }
}