<?php

namespace App\Serializer\Normalizer\Skill;

use App\Player\Domain\ValueObject\Skill\Strength;
use App\Serializer\Normalizer\BaseNormalizer;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService]
class StrengthNormalizer extends BaseNormalizer
{
    public function __construct()
    {
        parent::__construct(Strength::class);
    }
}