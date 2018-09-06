<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Ramsey\Collection\AbstractCollection;

class ChirpCollection extends AbstractCollection
{
    public function getType()
    {
        return Chirp::class;
    }

}