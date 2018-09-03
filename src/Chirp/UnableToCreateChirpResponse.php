<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\Response;

class UnableToCreateChirpResponse extends Response
{
    public function __construct(string $error)
    {
        $message = 'Unable to create Chirp: ' . $error;
        parent::__construct(Response::BAD_REQUEST, [], $message);
    }
}