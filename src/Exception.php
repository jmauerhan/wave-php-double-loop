<?php declare(strict_types=1);

namespace Chirper;

use \Exception AS CoreException;

class Exception extends CoreException
{
    const INVALID_JSON       = 100;
    const PERSISTENCE_DRIVER = 200;
}