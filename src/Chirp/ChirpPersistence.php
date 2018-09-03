<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Persistence\PersistenceDriverException;

interface ChirpPersistence
{
    /**
     * @param Chirp $chirp
     * @return Chirp
     *
     * @throws PersistenceDriverException
     */
    public function save(Chirp $chirp): Chirp;
}