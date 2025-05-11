<?php

namespace App\Tournament\Application\Presenter;


use App\Tournament\Domain\Entity\Tournament;

class TournamentPresenter
{
    public function present(Tournament $tournament): array
    {
        return [
            'id' => $tournament->getId(),
            'name' => $tournament->getName(),
            'startDate' => $tournament->getStartDate()->format('Y-m-d H:i:s'),
            'gender' => $tournament->getGenderTournament()->getValue(),
            'winner' => $tournament->getWinner() ? [
                'id' => $tournament->getWinner()->getId(),
                'name' => $tournament->getWinner()->getName(),
            ] : null,
        ];
    }

    public function presentCollection(array $tournaments): array
    {
        return array_map([$this, 'present'], $tournaments);
    }
}