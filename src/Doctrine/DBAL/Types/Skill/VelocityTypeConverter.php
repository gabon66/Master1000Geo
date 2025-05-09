<?php

namespace App\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\Skill\BaseSkillTypeConverter;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Velocity;

class VelocityTypeConverter extends BaseSkillTypeConverter
{
    public static string $supportedClass = Velocity::class;
    public static string $typeName = 'velocity';
}