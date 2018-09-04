<?php declare(strict_types=1);

namespace Chirper\Json;

use Chirper\Exception;
use Chirper\Http\Response;
use Throwable;

class InvalidJsonException extends Exception
{
    public function __construct(array $errors = null, Throwable $previous = null)
    {
        $errors  = $errors ?? [[
                                   'status' => Response::BAD_REQUEST,
                                   'title'  => 'Invalid JSON',
                                   'detail' => 'Invalid or missing JSON data structure'
                               ]];
        $message = json_encode((object)['errors' => $errors]);
        parent::__construct($message, 0, $previous);
    }
}