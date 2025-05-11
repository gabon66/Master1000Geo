<?php

namespace App\Tournament\Application\Query;

use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Application\Presenter\TournamentPresenter;
use App\Tournament\Domain\Entity\Tournament;
use App\Tournament\Domain\Repository\TournamentRepositoryInterface;

class GetTournamentsQueryHandler
{
    private TournamentRepositoryInterface $tournamentRepository;
    private TournamentPresenter $tournamentPresenter;

    public function __construct(TournamentRepositoryInterface $tournamentRepository, TournamentPresenter $tournamentPresenter)
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->tournamentPresenter = $tournamentPresenter;
    }

    /**
     * @return Tournament[]
     */
    public function __invoke(GetTournamentsQuery $query): array
    {
        $gender =  $startDate = $endDate = null;

        if ($query->getGender()) {
            $gender = (new Gender($query->getGender()))->getValue();
        }

        if ($query->getStartDate()) {
            try {
                $startDate = new \DateTimeImmutable($query->getStartDate());
            } catch (\Exception $e) {
                throw new \InvalidArgumentException('Invalid start date format.');
            }
        }

        if ($query->getEndDate()) {
            try {
                $endDate = new \DateTimeImmutable($query->getEndDate());
            } catch (\Exception $e) {
                throw new \InvalidArgumentException('Invalid start date format.');
            }
        }

        $tournaments = $this->tournamentRepository->findAllByCriteria(
            $gender,
            $startDate,
            $endDate
        );

        return $this->tournamentPresenter->presentCollection($tournaments);
    }
}