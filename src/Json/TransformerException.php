<?php declare(strict_types=1);

namespace Chirper\Json;

use Chirper\Exception;
use Throwable;

class TransformerException extends Exception
{
    public function __construct(string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, self::TRANSFORMER_EXCEPTION, $previous);
    }
}