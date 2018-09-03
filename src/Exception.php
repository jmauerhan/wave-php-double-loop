<?php declare(strict_types=1);

namespace Chirper;

use \Exception AS CoreException;

class Exception extends CoreException
{
    const INVALID_JSON          = 100;
    const INVALID_JSON_API      = 110;
    const TRANSFORMER_EXCEPTION = 120;

    const PERSISTENCE_DRIVER = 200;
}