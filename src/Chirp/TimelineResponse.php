<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\Response;

class TimelineResponse extends Response
{
    public function __construct(string $body)
    {
        parent::__construct(Response::OK, [], $body);
    }
}