<?php
// /src/Model/Enum/Enum.php

namespace Model\Enum;

/**
 * Class Enum
 *
 * Source: http://blog.igorvorobiov.com/2015/01/11/tip3-enums-in-php-or-an-alternative-solution-to-splenum/
 */
abstract class Enum
{
    /**
     * @var array
     */
    private static $constantsCache = [];

    /**
     * @var mixed
     */
    private $value;

    /**
     * Enum constructor.
     *
     * @param $value
     */
    public function __construct($value) {
        if (is_a($value, '\Model\Enum\Enum')) {
            $value = $value->value();
        }

        if (!self::has($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . get_called_class());
        }

        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function has($value) {
        return in_array($value, self::toArray(), true);
    }

    /**
     * @return mixed
     */
    public static function toArray() {
        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, self::$constantsCache)) {
            $reflection = new \ReflectionClass($calledClass);
            self::$constantsCache[$calledClass] = $reflection->getConstants();
        }

        return self::$constantsCache[$calledClass];
    }

    /**
     * Compares value of this enum to the value (of the enum) supplied.
     *
     * @param mixed|\Model\Enum\Enum $value
     * @return bool
     */
    public function is($value) {
        if (is_a($value, '\Model\Enum\Enum')) {
            $value = $value->value();
        }
        return $this->value === $value;
    }

    /**
     * Returns the inner value of the enum value.
     *
     * @return mixed
     */
    public function value() {
        return $this->value;
    }
}