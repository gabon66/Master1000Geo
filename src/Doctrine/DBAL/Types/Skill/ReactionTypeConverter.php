<?php

namespace App\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\Skill\BaseSkillTypeConverter;
use App\Player\Domain\ValueObject\Skill\Reaction;

class ReactionTypeConverter extends BaseSkillTypeConverter
{
    public const NAME = 'reaction';

    public function __construct()
    {
        parent::__construct(Reaction::class, self::NAME);
    }
}