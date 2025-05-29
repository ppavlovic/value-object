<?php

namespace unit\src;

use G4\ValueObject\Exception\MissingTimestampValueException;
use G4\ValueObject\Exception\InvalidTimestampValueException;
use G4\ValueObject\TimestampValue;
use G4\ValueObject\TimestampValueMilliSeconds;
use PHPUnit\Framework\TestCase;

class TimestampValueMillisecondsTest extends TestCase
{
    public function testMissingValueException(): void
    {
        $this->expectException(MissingTimestampValueException::class);
        new TimestampValueMilliSeconds('');
    }

    public function testInvalidValueFloatException(): void
    {
        $this->expectException(InvalidTimestampValueException::class);
        new TimestampValueMilliSeconds('1.0');
    }

    public function testInvalidValueHexaNumberException(): void
    {
        $this->expectException(InvalidTimestampValueException::class);
        new TimestampValueMilliSeconds('0xFF');
    }

    public function testValidValues(): void
    {
        $now = (int) (microtime(true) * 1000);
        $timestampInt = new TimestampValueMilliSeconds($now);
        $this->assertEquals($now, $timestampInt->getValue());

        $timestampString = new TimestampValueMilliSeconds('1748436857881');
        $this->assertEquals('1748436857881', (string) $timestampString);
    }

    public function testGetFormatted(): void
    {
        $timestamp = new TimestampValueMilliSeconds('1748436857881');
        $this->assertEquals('2025-05-28 14:54:17.881', $timestamp->getFormatted());
    }

    public function testGetSeconds(): void
    {
        $timestamp = new TimestampValueMilliSeconds(1748436857881);
        $this->assertEquals(1748436857, $timestamp->getSeconds());
    }

    public function testNow(): void
    {
        $time = time();
        $timestamp = TimestampValueMilliSeconds::now();
        $this->assertEquals($time, $timestamp->getSeconds());
    }

    public function testInvalidValueLengthLower(): void
    {
        $this->expectException(InvalidTimestampValueException::class);
        new TimestampValueMilliSeconds(123456789);
    }

    public function testInvalidValueLengthHigher(): void
    {
        $this->expectException(InvalidTimestampValueException::class);
        new TimestampValueMilliSeconds(12345678912345);
    }

    public function testFromTimestampValue(): void
    {
        $timestamp = TimestampValueMilliSeconds::fromTimestampValue(new TimestampValue(1748436857));
        $this->assertEquals(1748436857000, $timestamp->getValue());
    }
}
