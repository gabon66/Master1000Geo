<?php

namespace App\Serializer\Normalizer\Skill;

use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Serializer\Normalizer\BaseNormalizer;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService]
class ReactionNormalizer extends BaseNormalizer
{
    public function __construct()
    {
        parent::__construct(Reaction::class);
    }
}