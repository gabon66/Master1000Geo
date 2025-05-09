<?php

namespace App\Player\Domain\ValueObject\Skill;

class Reaction extends BaseSkill
{
    protected function getSkillName(): string
    {
        return 'reaction';
    }
}