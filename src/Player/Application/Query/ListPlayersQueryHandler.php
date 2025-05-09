<?php

namespace App\Player\Application\Query;

use App\Player\Application\Query\ListPlayersQuery;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

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
        $criteria = [];
        if ($query->getGender() !== null) {
            $criteria['gender'] = $query->getGender();
        }

        $orderBy = [];
        if ($query->getOrderBy() !== null) {
            $orderBy[$query->getOrderBy()] = $query->getOrderDirection();
        }

        $limit = $query->getLimit();

        return $this->playerRepository->findBy($criteria, $orderBy, $limit);
    }
}