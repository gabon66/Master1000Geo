<?php

namespace App\Tests\Player\Domain\ValueObject;

use App\Player\Domain\ValueObject\Skill\Strength;

class StrengthTest extends SkillValueObjectTest
{
    protected string $className = Strength::class;
    protected int $minValue = 0;
    protected int $maxValue = 100;

    protected function createSkillObject(int $value): object
    {
        return new Strength($value);
    }
}