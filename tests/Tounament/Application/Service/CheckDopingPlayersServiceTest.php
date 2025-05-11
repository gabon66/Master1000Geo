<?php

namespace App\Tests\Tounament\Application\Service;

use App\Player\Domain\Entity\Player;
use App\Tournament\Application\Service\CheckDopingPlayersService;
use App\Tournament\Domain\Enum\TournamentRules;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CheckDopingPlayersServiceTest extends TestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->dopingProbability = TournamentRules::DOPING_PROBABILITY_PERCENTAGE->value / 100;
        $this->checkDopingService = new CheckDopingPlayersService($this->entityManager);
    }

    public function testCheckNoDopingPositive(): void
    {
        $winner = new Player();
        $winner->setPoints(100);
        $loser = new Player();
        $loser->setPoints(50);

        $mockedService = $this->getMockBuilder(CheckDopingPlayersService::class)
            ->setConstructorArgs([$this->entityManager])
            ->onlyMethods(['getRandomDopingResult', 'selectPlayerToTest', 'resetPoints'])
            ->getMock();
        $mockedService->expects($this->once())->method('getRandomDopingResult')->willReturn(false);
        $mockedService->expects($this->once())->method('selectPlayerToTest')->willReturn($winner);
        $mockedService->expects($this->never())->method('resetPoints');
        $this->entityManager->expects($this->never())->method('flush');

        $result = $mockedService->check($winner, $loser);
        $this->assertSame($winner, $result);
    }

    public function testCheckWinnerDopingPositive(): void
    {
        $winner = new Player();
        $winner->setPoints(100);
        $loser = new Player();
        $loser->setPoints(50);

        $mockedService = $this->getMockBuilder(CheckDopingPlayersService::class)
            ->setConstructorArgs([$this->entityManager])
            ->onlyMethods(['getRandomDopingResult', 'selectPlayerToTest', 'resetPoints'])
            ->getMock();
        $mockedService->expects($this->once())->method('getRandomDopingResult')->willReturn(true);
        $mockedService->expects($this->once())->method('selectPlayerToTest')->willReturn($winner);
        $mockedService->expects($this->once())->method('resetPoints')->with($this->equalTo($winner));

        $result = $mockedService->check($winner, $loser);
        $this->assertSame($loser, $result);
    }

    public function testCheckLoserDopingPositive(): void
    {
        $winner = new Player();
        $winner->setPoints(100);
        $loser = new Player();
        $loser->setPoints(50);

        $mockedService = $this->getMockBuilder(CheckDopingPlayersService::class)
            ->setConstructorArgs([$this->entityManager])
            ->onlyMethods(['getRandomDopingResult', 'selectPlayerToTest', 'resetPoints'])
            ->getMock();
        $mockedService->expects($this->once())->method('getRandomDopingResult')->willReturn(true);
        $mockedService->expects($this->once())->method('selectPlayerToTest')->willReturn($loser);
        $mockedService->expects($this->once())->method('resetPoints')->with($this->equalTo($loser));

        $result = $mockedService->check($winner, $loser);
        $this->assertSame($winner, $result);
    }
}