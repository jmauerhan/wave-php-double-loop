<?php declare(strict_types=1);

namespace Chirper\Http;

class InternalServerErrorResponse extends Response
{
    public function __construct(string $message)
    {

        $message = json_encode(
            [
                'errors' => [
                    'status' => Response::INTERNAL_SERVER_ERROR,
                    'title'  => 'Internal Server Error',
                    'detail' => $message
                ]
            ]
        );
        parent::__construct(Response::INTERNAL_SERVER_ERROR, [], $message);
    }
}