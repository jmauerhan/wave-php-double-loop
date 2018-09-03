<?php declare(strict_types=1);

namespace Chirper\Http;

class InternalServerErrorResponse extends Response
{
    public function __construct()
    {
        parent::__construct(Response::INTERNAL_SERVER_ERROR);
    }
}