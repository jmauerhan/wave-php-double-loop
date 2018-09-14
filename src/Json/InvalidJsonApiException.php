<?php declare(strict_types=1);

namespace Chirper\Json;

use Chirper\Http\Response;
use Throwable;

class InvalidJsonApiException extends InvalidJsonException
{
    public function __construct(array $errors, Throwable $previous = null)
    {
        $errors = array_values(
            array_map(
                function ($error) {
                    return [
                        'status' => Response::CLIENT_CONFLICT,
                        'title'  => $error['error'],
                        'source' => (object)['pointer' => $error['property']]
                    ];
                },
                $errors)
        );

        parent::__construct($errors, $previous);
    }
}