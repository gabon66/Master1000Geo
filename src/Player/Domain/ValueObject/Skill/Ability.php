<?php

namespace App\Player\Domain\ValueObject\Skill;

class Ability extends BaseSkill
{
    protected function getSkillName(): string
    {
        return 'ability';
    }
}