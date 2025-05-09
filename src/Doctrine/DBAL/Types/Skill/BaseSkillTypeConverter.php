<?php

namespace App\Doctrine\DBAL\Types\Skill;

use App\Player\Domain\ValueObject\Skill\BaseSkill;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;
use InvalidArgumentException;

abstract class BaseSkillTypeConverter extends SmallIntType
{
    public static string $supportedClass;
    public static string $typeName;

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?BaseSkill
    {
        return $value === null ? null : new static::$supportedClass($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        return $value instanceof static::$supportedClass ? $value->getValue() : null;
    }

    public function getName(): string
    {
        return static::$typeName;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
