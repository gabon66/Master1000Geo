<?php

namespace App\Doctrine\DBAL\Types\Skill;

use App\Player\Domain\ValueObject\Skill\BaseSkill;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;
use InvalidArgumentException;

abstract class BaseSkillTypeConverter extends SmallIntType
{
    private string $supportedClass;
    private string $typeName;

    public function __construct(string $supportedClass, string $typeName)
    {
        $this->supportedClass = $supportedClass;
        $this->typeName = $typeName;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?BaseSkill
    {
        return $value === null ? null : new $this->supportedClass($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        return $value instanceof $this->supportedClass ? $value->getValue() : null;
    }

    public function getName(): string
    {
        return $this->typeName;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
