<?php

namespace App\Player\Domain\ValueObject;

use InvalidArgumentException;

class Gender
{
    private string $value;

    private const ALLOWED_VALUES = ['male', 'female'];

    public function __construct(string $value)
    {
        $normalizedValue = strtolower(trim($value));
        if (!in_array($normalizedValue, self::ALLOWED_VALUES, true)) {
            throw new InvalidArgumentException(sprintf('The gender "%s" is invalid. Allowed values are: %s', $value, implode(', ', self::ALLOWED_VALUES)));
        }
        $this->value = $normalizedValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Gender $other): bool
    {
        return $this->value === $other->value;
    }
}