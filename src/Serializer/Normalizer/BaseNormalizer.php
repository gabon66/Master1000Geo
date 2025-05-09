<?php

namespace App\Serializer\Normalizer;

use http\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class BaseNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private string $supportedClass;

    public function __construct(string $supportedClass)
    {
        $this->supportedClass = $supportedClass;
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!$object instanceof $this->supportedClass) {
            throw new InvalidArgumentException(sprintf('The object must be an instance of %s', $this->supportedClass));
        }
        return $object->getValue();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof $this->supportedClass;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if ($type !== $this->supportedClass) {
            throw new InvalidArgumentException(sprintf('The data must be denormalized into %s', $this->supportedClass));
        }
        return new $this->supportedClass($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === $this->supportedClass && is_numeric($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            $this->supportedClass => true,
        ];
    }
}