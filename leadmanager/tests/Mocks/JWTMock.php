<?php

namespace Tests\Mocks;

use Firebase\JWT\Key;
use Exception;

class JWTMock
{
    private static $shouldThrow = false;
    private static $returnValue;

    public function setThrowException(bool $should): void
    {
        self::$shouldThrow = $should;
    }

    public function setReturnValue($value): void
    {
        self::$returnValue = $value;
    }

    public static function decode(string $jwt, Key $keyInput)
    {
        if (self::$shouldThrow) {
            throw new Exception('Invalid token');
        }
        
        return self::$returnValue ?? (object) ['sub' => 'test_identity'];
    }
}