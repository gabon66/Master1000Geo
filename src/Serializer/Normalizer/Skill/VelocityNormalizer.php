<?php

namespace App\Serializer\Normalizer\Skill;

use App\Player\Domain\ValueObject\Skill\Velocity;
use App\Serializer\Normalizer\BaseNormalizer;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService]
class VelocityNormalizer extends BaseNormalizer
{
    public function __construct()
    {
        parent::__construct(Velocity::class);
    }
}