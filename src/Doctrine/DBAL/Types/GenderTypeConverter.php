<?php

namespace App\Doctrine\DBAL\Types;

use App\Player\Domain\ValueObject\Gender;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class GenderTypeConverter extends StringType
{
    public const NAME = 'gender'; // Nombre único para el Type

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Gender
    {
        if ($value === null) {
            return null;
        }

        return new Gender($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Gender ? $value->getValue() : null;
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