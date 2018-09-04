<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Json\InvalidJsonException;
use Chirper\Json\TransformerException;

interface JsonChirpTransformer
{
    /**
     * @param string $json
     * @return Chirp
     *
     * @throws InvalidJsonException
     */
    public function toChirp(string $json): Chirp;

    /**
     * @param Chirp $chirp
     * @return string
     *
     * @throws TransformerException
     */
    public function toJson(Chirp $chirp): string;
}