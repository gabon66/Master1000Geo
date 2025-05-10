<?php

namespace App\Serializer\Normalizer\Skill;

use App\Player\Domain\ValueObject\Skill\Ability;
use App\Serializer\Normalizer\BaseNormalizer;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService]
class AbilityNormalizer extends BaseNormalizer
{
    public function __construct()
    {
        parent::__construct(Ability::class);
    }
}