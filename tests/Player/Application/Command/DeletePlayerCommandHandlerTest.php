<?php

namespace App\Tests\Player\Application\Command;

use App\Player\Application\Command\DeletePlayerCommand;
use App\Player\Application\Command\DeletePlayerCommandHandler;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeletePlayerCommandHandlerTest extends TestCase
{
    private $playerRepository;
    private $entityManager;
    private $commandHandler;

    protected function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->commandHandler = new DeletePlayerCommandHandler($this->playerRepository, $this->entityManager);
    }

    public function testHandleDeletesExistingPlayer(): void
    {
        $playerId = 123;
        $command = new DeletePlayerCommand($playerId);

        $existingPlayer = new Player();

        $this->playerRepository->expects($this->once())
            ->method('find')
            ->with($playerId)
            ->willReturn($existingPlayer);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($existingPlayer));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->commandHandler->__invoke($command);

    }

    public function testHandleThrowsNotFoundExceptionIfPlayerDoesNotExist(): void
    {
        $playerId = 456;
        $command = new DeletePlayerCommand($playerId);

        $this->playerRepository->expects($this->once())
            ->method('find')
            ->with($playerId)
            ->willReturn(null);

        $this->entityManager->expects($this->never())
            ->method('remove');
        $this->entityManager->expects($this->never())
            ->method('flush');

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf('Player with ID "%s" not found.', $playerId));

        $this->commandHandler->__invoke($command);
    }
}