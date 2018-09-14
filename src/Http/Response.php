<?php declare(strict_types=1);

namespace Chirper\Http;

use GuzzleHttp\Psr7\Response AS Psr7Response;

class Response extends Psr7Response
{
    const OK                    = 200;
    const CREATED               = 201;
    const BAD_REQUEST           = 400;
    const CLIENT_CONFLICT       = 409;
    const INTERNAL_SERVER_ERROR = 500;

    public function __construct(int $status = 200,
                                array $headers = [],
                                $body = null,
                                string $version = '1.1',
                                ?string $reason = null)
    {
        $headers = array_merge($headers, ['content-type' => 'text/json']);
        parent::__construct($status, $headers, $body, $version, $reason);
    }
}