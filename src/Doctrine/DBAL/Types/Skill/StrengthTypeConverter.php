<?php

namespace App\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\Skill\BaseSkillTypeConverter;
use App\Player\Domain\ValueObject\Skill\Strength;

class StrengthTypeConverter extends BaseSkillTypeConverter
{
    public static string $supportedClass = Strength::class;
    public static string $typeName = 'strength';
}