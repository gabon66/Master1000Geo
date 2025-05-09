<?php

namespace App\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\Skill\BaseSkillTypeConverter;
use App\Player\Domain\ValueObject\Skill\Strength;

class StrengthTypeConverter extends BaseSkillTypeConverter
{
    public const NAME = 'strength';

    public function __construct()
    {
        parent::__construct(Strength::class, self::NAME);
    }
}