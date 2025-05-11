<?php

namespace App\Tests\Player\Domain\ValueObject;

use App\Player\Domain\ValueObject\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    public function testValidMaleGender(): void
    {
        $gender = new Gender('male');
        $this->assertEquals('male', $gender->getValue());
    }

    public function testValidFemaleGender(): void
    {
        $gender = new Gender('female');
        $this->assertEquals('female', $gender->getValue());
    }

    public function testInvalidGenderValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Gender('other');
    }
}