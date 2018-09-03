<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Transform\InvalidJsonException;
use Chirper\Transform\TransformerException;

class JsonApiChirpTransformer implements JsonChirpTransformer
{
    /**
     * @param Chirp $chirp
     * @return string
     *
     * @throws InvalidJsonException
     */
    public function toJson(Chirp $chirp): string
    {
        return '';
    }

    /**
     * @param string $json
     * @return Chirp
     *
     * @throws TransformerException
     */
    public function toChirp(string $json): Chirp
    {

    }
}