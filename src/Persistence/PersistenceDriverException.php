<?php declare(strict_types=1);

namespace Chirper\Persistence;

use Throwable;

class PersistenceDriverException extends \Exception
{
    public function __construct(string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}