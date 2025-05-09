<?php

namespace App\Player\Domain\ValueObject;

use InvalidArgumentException;

class Age
{
    private int $value;

    public function __construct(?int $value)
    {
        if ($value === null) {
            throw new InvalidArgumentException('La edad del jugador es mandatoria');
        }

        if ($value < 13 || $value > 90) {
            throw new InvalidArgumentException('La edad del jugador debe estar entre 13 y 90 años.');
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
}