<?php

namespace App\Tests\Player\Domain\ValueObject;

use PHPUnit\Framework\TestCase;

abstract class SkillValueObjectTest extends TestCase
{
    protected string $className;
    protected int $minValue;
    protected int $maxValue;

    abstract protected function createSkillObject(int $value): object;

    public function testValidValue(): void
    {
        $validValue = $this->minValue + floor(($this->maxValue - $this->minValue) / 2);
        $skillObject = $this->createSkillObject($validValue);
        $this->assertEquals($validValue, $skillObject->getValue());
    }

    public function testInvalidValueTooLow(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->createSkillObject($this->minValue - 1);
    }

    public function testInvalidValueTooHigh(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->createSkillObject($this->maxValue + 1);
    }
}