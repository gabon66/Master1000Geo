<?php

namespace App\Doctrine\DBAL\Types\Skill;

use App\Doctrine\DBAL\Types\Skill\BaseSkillTypeConverter;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Strength;

class ReactionTypeConverter extends BaseSkillTypeConverter
{
    public static string $supportedClass = Reaction::class;
    public static string $typeName = 'reaction';
}