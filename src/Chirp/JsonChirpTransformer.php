<?php declare(strict_types=1);

namespace Chirper\Chirp;

interface JsonChirpTransformer
{
    /**
     * @param string $json
     * @return Chirp
     *
     * @throws InvalidJsonException
     */
    public function toChirp(string $json): Chirp;
}