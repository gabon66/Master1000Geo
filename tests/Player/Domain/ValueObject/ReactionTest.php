<?php

namespace App\Tests\Player\Domain\ValueObject;

use App\Player\Domain\ValueObject\Skill\Reaction;

class ReactionTest  extends SkillValueObjectTest
{
    protected string $className = Reaction::class;
    protected int $minValue = 0;
    protected int $maxValue = 100;

    protected function createSkillObject(int $value): object
    {
        return new Reaction($value);
    }
}