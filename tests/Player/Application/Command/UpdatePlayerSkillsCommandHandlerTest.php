<?php

namespace App\Tests\Player\Application\Command;

use App\Player\Application\Command\UpdatePlayerSkillsCommand;
use App\Player\Application\Command\UpdatePlayerSkillsCommandHandler;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Player\Domain\ValueObject\Skill\Ability;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Strength;
use App\Player\Domain\ValueObject\Skill\Velocity;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdatePlayerSkillsCommandHandlerTest extends TestCase
{
    private $playerRepository;
    private $entityManager;
    private $commandHandler;

    protected function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->commandHandler = new UpdatePlayerSkillsCommandHandler($this->playerRepository, $this->entityManager);
    }

    public function testHandleUpdatesExistingPlayerSkills(): void
    {
        $playerId = 123;
        $command = new UpdatePlayerSkillsCommand($playerId, 90, 90, 95, 98);

        $existingPlayer = new Player();
        $existingPlayer->setStrength(new Strength(80));
        $existingPlayer->setVelocity(new Velocity(85));
        $existingPlayer->setReaction(new Reaction(88));
        $existingPlayer->setAbility(new Ability(90));

        $this->playerRepository->expects($this->once())
            ->method('find')
            ->with($playerId)
            ->willReturn($existingPlayer);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $updatedPlayer = $this->commandHandler->__invoke($command);

        $this->assertSame($existingPlayer, $updatedPlayer);
        $this->assertEquals(90, $updatedPlayer->getStrength()->getValue());
        $this->assertEquals(95, $updatedPlayer->getVelocity()->getValue());
        $this->assertEquals(98, $updatedPlayer->getReaction()->getValue());
        $this->assertEquals(90, $updatedPlayer->getAbility()->getValue());
    }

    public function testHandleUpdatesOnlyProvidedSkills(): void
    {
        $playerId = 456;
        $command = new UpdatePlayerSkillsCommand($playerId, null, null, 91, null);

        $existingPlayer = new Player();
        $existingPlayer->setStrength(new Strength(78));
        $existingPlayer->setVelocity(new Velocity(82));
        $existingPlayer->setReaction(new Reaction(85));
        $existingPlayer->setAbility(new Ability(89));

        $this->playerRepository->expects($this->once())
            ->method('find')
            ->with($playerId)
            ->willReturn($existingPlayer);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $updatedPlayer = $this->commandHandler->__invoke($command);

        $this->assertSame($existingPlayer, $updatedPlayer);
        $this->assertEquals(78, $updatedPlayer->getStrength()->getValue()); // No cambió
        $this->assertEquals(91, $updatedPlayer->getVelocity()->getValue()); // No cambió
        $this->assertEquals(85, $updatedPlayer->getReaction()->getValue()); // Cambió
        $this->assertEquals(89, $updatedPlayer->getAbility()->getValue()); // No cambió
    }

    public function testHandleThrowsNotFoundExceptionIfPlayerDoesNotExist(): void
    {
        $playerId = 789;
        $command = new UpdatePlayerSkillsCommand($playerId, 88, 90, 93, 96);

        $this->playerRepository->expects($this->once())
            ->method('find')
            ->with($playerId)
            ->willReturn(null);

        $this->entityManager->expects($this->never())
            ->method('flush');

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf('Player with ID "%s" not found.', $playerId));

        $this->commandHandler->__invoke($command);
    }
}