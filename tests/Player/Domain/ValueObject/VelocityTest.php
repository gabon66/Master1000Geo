<?php

namespace App\Tests\Player\Domain\ValueObject;

use App\Player\Domain\ValueObject\Skill\Strength;
use App\Player\Domain\ValueObject\Skill\Velocity;
use PHPUnit\Framework\TestCase;

class VelocityTest extends SkillValueObjectTest
{
    protected string $className = Velocity::class;
    protected int $minValue = 0;
    protected int $maxValue = 100;

    protected function createSkillObject(int $value): object
    {
        return new Velocity($value);
    }
}