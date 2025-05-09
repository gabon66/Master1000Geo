<?php

namespace App\Serializer\Normalizer;

use App\Player\Domain\ValueObject\Gender;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService]
class GenderNormalizer extends BaseNormalizer
{
    public function __construct()
    {
        parent::__construct(Gender::class);
    }
}