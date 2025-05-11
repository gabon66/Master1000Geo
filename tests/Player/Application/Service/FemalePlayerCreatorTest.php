<?php

namespace App\Tests\Player\Application\Service;

use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Application\Service\FemalePlayerCreator;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Gender;
use PHPUnit\Framework\TestCase;

class FemalePlayerCreatorTest extends  TestCase
{
    private FemalePlayerCreator $playerCreator;

    protected function setUp(): void
    {
        $this->playerCreator = new FemalePlayerCreator();
    }

    public function testCreateFemalePlayerSuccessfully(): void
    {
        $command = new CreatePlayerCommand('Serena Williams', 'female', 80, 88, 92, 95, 35);
        $player = $this->playerCreator->create($command);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals('Serena Williams', $player->getName());
        $this->assertEquals(new Gender('female'), $player->getGender());
        $this->assertEquals(80, $player->getStrength()?->getValue());
        $this->assertEquals(88, $player->getVelocity()?->getValue());
        $this->assertEquals(92, $player->getReaction()->getValue());
        $this->assertEquals(95, $player->getAbility()?->getValue());
        $this->assertEquals(35, $player->getAge()->getValue());
    }

    public function testCreateFemalePlayerThrowsExceptionIfReactionIsMissing(): void
    {
        $command = new CreatePlayerCommand('Serena Williams', 'female', 80, 88, null, 95, 35);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Reaction is required for female players.');

        $this->playerCreator->create($command);
    }

    public function testCreateFemalePlayerAllowsMissingStrength(): void
    {
        $command = new CreatePlayerCommand('Serena Williams', 'female', null, 88, 92, 95, 35);
        $player = $this->playerCreator->create($command);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertNull($player->getStrength());
    }

    public function testCreateFemalePlayerAllowsMissingVelocity(): void
    {
        $command = new CreatePlayerCommand('Serena Williams', 'female', 80, null, 92, 95, 35);
        $player = $this->playerCreator->create($command);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertNull($player->getVelocity());
    }
}