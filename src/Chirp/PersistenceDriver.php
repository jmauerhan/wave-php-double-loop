<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Persistence\PersistenceDriverException;

interface PersistenceDriver
{
    /**
     * @param Chirp $chirp
     * @return bool
     *
     * @throws PersistenceDriverException
     */
    public function save(Chirp $chirp): bool;
}