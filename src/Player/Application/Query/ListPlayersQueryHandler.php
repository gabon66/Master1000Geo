<?php

namespace App\Player\Application\Query;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;

#[AsMessageHandler]
class ListPlayersQueryHandler
{
    private PlayerRepositoryInterface $playerRepository;

    public function __construct(PlayerRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    /**
     * @return array<Player>
     */
    public function __invoke(ListPlayersQuery $query): array
    {
        $gender = $query->getGender();
        if ($gender !== null) {
            return $this->playerRepository->findBy(['gender' => $gender]);
        }

        return $this->playerRepository->findAll();
    }
}