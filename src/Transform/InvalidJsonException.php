<?php declare(strict_types=1);

namespace Chirper\Transform;

use Chirper\Exception;
use Throwable;

class InvalidJsonException extends Exception
{
    public function __construct(string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, self::INVALID_JSON, $previous);
    }
}