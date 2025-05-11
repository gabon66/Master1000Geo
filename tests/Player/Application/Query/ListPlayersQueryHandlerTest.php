<?php

namespace App\Tests\Player\Application\Query;

use App\Player\Application\Query\ListPlayersQuery;
use App\Player\Application\Query\ListPlayersQueryHandler;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\Enum\LimitsList;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Player\Domain\ValueObject\Gender;
use PHPUnit\Framework\TestCase;

class ListPlayersQueryHandlerTest extends TestCase
{
    private $playerRepository;
    private $queryHandler;

    protected function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepositoryInterface::class);
        $this->queryHandler = new ListPlayersQueryHandler($this->playerRepository);
    }

    public function testHandleReturnsPlayersWithOptionalFiltersAndSorting(): void
    {
        $gender = new Gender('female');
        $orderBy = 'points';
        $orderDirection = 'DESC';
        $limit = LimitsList::TOP_PLAYERS->value;

        $player1 = new Player();
        $player1->setName('Serena Williams');
        $player1->setGender($gender);
        $player1->setPoints(15000);

        $player2 = new Player();
        $player2->setName('Venus Williams');
        $player2->setGender($gender);
        $player2->setPoints(14000);

        $players = [$player1, $player2];

        $this->playerRepository->expects($this->once())
            ->method('findBy')
            ->with(['gender' => $gender], [$orderBy => $orderDirection], $limit)
            ->willReturn($players);

        $query = new ListPlayersQuery($gender, $orderBy, $orderDirection, $limit);
        $result = $this->queryHandler->__invoke($query);

        $this->assertEquals($players, $result);
    }

    public function testHandleReturnsAllPlayersWithoutFilters(): void
    {
        $player1 = new Player();
        $player1->setName('Novak Djokovic');
        $player1->setGender(new Gender('male'));
        $player1->setPoints(16000);

        $player2 = new Player();
        $player2->setName('Iga Świątek');
        $player2->setGender(new Gender('female'));
        $player2->setPoints(15500);

        $allPlayers = [$player1, $player2];

        $this->playerRepository->expects($this->once())
            ->method('findBy')
            ->with([], [], null)
            ->willReturn($allPlayers);

        $query = new ListPlayersQuery(null, null, null, null);
        $result = $this->queryHandler->__invoke($query);

        $this->assertEquals($allPlayers, $result);
    }
}