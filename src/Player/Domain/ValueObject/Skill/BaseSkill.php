<?php

namespace App\Player\Domain\ValueObject\Skill;

use InvalidArgumentException;

abstract class BaseSkill
{
    protected int $value;

    public function __construct(int $value)
    {
        if ($value < 0 || $value > 100) {
            throw new InvalidArgumentException(sprintf('The %s must be between 0 and 100, got %d.', $this->getSkillName(), $value));
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    abstract protected function getSkillName(): string;
}