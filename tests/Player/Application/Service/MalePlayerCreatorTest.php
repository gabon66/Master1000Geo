<?php

namespace App\Tests\Player\Application\Service;

use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Application\Service\MalePlayerCreator;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Gender;
use PHPUnit\Framework\TestCase;

class MalePlayerCreatorTest extends TestCase
{
    private MalePlayerCreator $playerCreator;

    protected function setUp(): void
    {
        $this->playerCreator = new MalePlayerCreator();
    }

    public function testCreateMalePlayerSuccessfully(): void
    {
        $command = new CreatePlayerCommand('John Doe', 'male', 85, 92, 78, 90, 28);
        $player = $this->playerCreator->create($command);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals('John Doe', $player->getName());
        $this->assertEquals(new Gender('male'), $player->getGender());
        $this->assertEquals(85, $player->getStrength()->getValue());
        $this->assertEquals(92, $player->getVelocity()->getValue());
        $this->assertEquals(78, $player->getReaction()->getValue());
        $this->assertEquals(90, $player->getAbility()?->getValue());
        $this->assertEquals(28, $player->getAge()->getValue());
    }

    public function testCreateMalePlayerThrowsExceptionIfStrengthIsMissing(): void
    {
        $command = new CreatePlayerCommand('John Doe', 'male', null, 92, 78, 90, 28);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Strength is required for male players.');

        $this->playerCreator->create($command);
    }

    public function testCreateMalePlayerThrowsExceptionIfVelocityIsMissing(): void
    {
        $command = new CreatePlayerCommand('John Doe', 'male', 85, null, 78, 90, 28);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Velocity is required for male players.');

        $this->playerCreator->create($command);
    }

    public function testCreateMalePlayerAllowsMissingReaction(): void
    {
        $command = new CreatePlayerCommand('John Doe', 'male', 85, 92, null, 90, 28);
        $player = $this->playerCreator->create($command);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertNull($player->getReaction());
    }
}