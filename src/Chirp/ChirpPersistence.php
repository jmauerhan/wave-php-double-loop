<?php declare(strict_types=1);

namespace Chirper\Chirp;

interface ChirpPersistence
{
    public function save(Chirp $chirp): Chirp;
}