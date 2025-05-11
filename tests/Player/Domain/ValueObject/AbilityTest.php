<?php

namespace App\Tests\Player\Domain\ValueObject;

use App\Player\Domain\ValueObject\Skill\Ability;

class AbilityTest extends SkillValueObjectTest
{
    protected string $className = Ability::class;
    protected int $minValue = 0;
    protected int $maxValue = 100;

    protected function createSkillObject(int $value): object
    {
        return new Ability($value);
    }
}