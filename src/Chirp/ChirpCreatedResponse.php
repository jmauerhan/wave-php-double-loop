<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\Response;

class ChirpCreatedResponse extends Response
{
    public function __construct(string $json)
    {
        parent::__construct(Response::CREATED, [], $json);
    }
}