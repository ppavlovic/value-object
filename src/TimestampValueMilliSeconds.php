<?php

namespace G4\ValueObject;

use G4\ValueObject\Exception\MissingTimestampValueException;
use G4\ValueObject\Exception\InvalidTimestampValueException;

class TimestampValueMilliSeconds implements StringInterface, NumberInterface
{
    const MILLISECONDS_MIN_LENGTH = 10;
    const MILLISECONDS_MAX_LENGTH = 13;

    /**
     * @var int
     */
    private $value;

    /**
     * @param $value
     * @throws MissingTimestampValueException
     * @throws InvalidTimestampValueException
     */
    public function __construct($value)
    {
        if (empty($value)) {
            throw new MissingTimestampValueException();
        }

        if (!self::isValid($value)) {
            throw new InvalidTimestampValueException($value);
        }

        $this->value = (int) $value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getSeconds()
    {
        return (int) ($this->value / 1000);
    }

    /**
     * @return string
     */
    public function getFormatted()
    {
        $seconds = (int) ($this->value / 1000);
        $milliseconds = $this->value % 1000;
        $millisecondsPadded = str_pad($milliseconds, 3, '0', STR_PAD_LEFT);
        return date('Y-m-d H:i:s', $seconds) . '.' . $millisecondsPadded;
    }

    /**
     * @param TimestampValue $timestampValue
     * @return self
     * @throws InvalidTimestampValueException
     * @throws MissingTimestampValueException
     */
    public static function fromTimestampValue(TimestampValue $timestampValue)
    {
        return new self($timestampValue->getValue() * 1000);
    }

    /**
     * @return self
     * @throws InvalidTimestampValueException
     * @throws MissingTimestampValueException
     */
    public static function now()
    {
        return new self((int) (microtime(true) * 1000));
    }

    /**
     * @param $timestamp
     * @return bool
     */
    public static function isValid($timestamp)
    {

        if (strlen((string) $timestamp) < self::MILLISECONDS_MIN_LENGTH
            || strlen((string) $timestamp) > self::MILLISECONDS_MAX_LENGTH
        ) {
            return false;
        }

        $check = (is_int($timestamp) || is_float($timestamp))
            ? $timestamp
            : (string) (int) $timestamp;

        return $check === $timestamp;
    }
}
