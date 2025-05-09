<?php

namespace App\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\Skill\BaseSkillTypeConverter;
use App\Player\Domain\ValueObject\Skill\Velocity;

class VelocityTypeConverter extends BaseSkillTypeConverter
{
    public const NAME = 'velocity';

    public function __construct()
    {
        parent::__construct(Velocity::class, self::NAME);
    }
}