<?php

namespace App\Player\Domain\ValueObject\Skill;

class Velocity extends BaseSkill
{
    protected function getSkillName(): string
    {
        return 'velocity';
    }
}