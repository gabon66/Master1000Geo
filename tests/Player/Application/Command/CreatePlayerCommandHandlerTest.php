<?php

namespace App\Tests\Player\Application\Command;

use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Application\Command\CreatePlayerCommandHandler;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Player\Domain\ValueObject\Age;
use App\Player\Domain\ValueObject\Gender;
use App\Player\Domain\ValueObject\Skill\Ability;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Strength;
use App\Player\Domain\ValueObject\Skill\Velocity;
use PHPUnit\Framework\TestCase;

class CreatePlayerCommandHandlerTest extends TestCase
{
    private $playerRepository;
    private $commandHandler;

    protected function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepositoryInterface::class);
        $this->commandHandler = new CreatePlayerCommandHandler($this->playerRepository);
    }

    public function testHandleCreatesAndPersistsNewPlayer(): void
    {
        $command = new CreatePlayerCommand(
            'Novak Djokovic',
            'male',
            85, // strength
            90, // velocity
            92, // reaction
            95, // ability
            38  // age
        );
        $genderVO = new Gender('male');
        $expectedPlayer = new Player();
        $expectedPlayer->setName('Novak Djokovic');
        $expectedPlayer->setGender($genderVO);
        $expectedPlayer->setStrength(new Strength(85));
        $expectedPlayer->setVelocity(new Velocity(90));
        $expectedPlayer->setReaction(new Reaction(92));
        $expectedPlayer->setAbility(new Ability(95));
        $expectedPlayer->setAge(new Age(38));
        $expectedPlayer->setPoints(0); // Por defecto al crear

        $this->playerRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($expectedPlayer));

        $this->commandHandler->__invoke($command);
    }
}