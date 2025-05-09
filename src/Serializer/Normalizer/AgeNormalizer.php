<?php

namespace App\Serializer\Normalizer;

use App\Player\Domain\ValueObject\Age;

#[AsService]
class AgeNormalizer extends BaseNormalizer
{
    public function __construct()
    {
        parent::__construct(Age::class);
    }
}