<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\Response;

class UnableToCreateChirpResponse extends Response
{
    public function __construct($body = null)
    {
        $status = Response::BAD_REQUEST;
        parent::__construct($status, [], $body);
    }
}