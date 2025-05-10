<?php

namespace App\Doctrine\DBAL\Types\Skill;

use App\Player\Domain\ValueObject\Skill\Ability;
class AbilityTypeConverter extends BaseSkillTypeConverter
{
    public static string $supportedClass = Ability::class;
    public static string $typeName = 'ability';
}