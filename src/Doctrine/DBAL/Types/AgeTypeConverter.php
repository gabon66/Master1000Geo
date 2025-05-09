<?php

namespace App\Doctrine\DBAL\Types;

use App\Player\Domain\ValueObject\Age;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;
use InvalidArgumentException;

class AgeTypeConverter extends SmallIntType
{
    public const NAME = 'age';

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Age
    {
        if ($value === null) {
            return null;
        }

        try {
            return new Age($value);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException("No se puede convertir el valor de la base de datos a un objeto Age: " . $e->getMessage());
        }
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        return $value instanceof Age ? $value->getValue() : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}