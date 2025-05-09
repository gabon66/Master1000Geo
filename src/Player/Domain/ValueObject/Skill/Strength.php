<?php

namespace App\Player\Domain\ValueObject\Skill;

class Strength extends BaseSkill
{
    protected function getSkillName(): string
    {
        return 'strength';
    }
}