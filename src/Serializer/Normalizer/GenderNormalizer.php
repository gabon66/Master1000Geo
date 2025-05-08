<?php

namespace App\Serializer\Normalizer;

use App\Player\Domain\ValueObject\Gender;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService]
class GenderNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!$object instanceof Gender) {
            throw new InvalidArgumentException('The object must be an instance of ' . Gender::class);
        }
        return $object->getValue();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Gender;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if ($type !== Gender::class) {
            throw new InvalidArgumentException('The data must be denormalized into ' . Gender::class);
        }
        return new Gender($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Gender::class && is_string($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Gender::class => true,
        ];
    }
}