<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\Request;
use Chirper\Http\Response;

class CreateAction
{
    /** @var JsonChirpTransformer */
    private $transformer;

    /** @var ChirpPersistence */
    private $persistence;

    public function __construct(JsonChirpTransformer $transformer, ChirpPersistence $persistence)
    {
        $this->transformer = $transformer;
        $this->persistence = $persistence;
    }

    public function create(Request $request): Response
    {
        $json = $request->getBody()->getContents();
        try {
            $chirp = $this->transformer->toChirp($json);
            $this->persistence->save($chirp);
        } catch (InvalidJsonException $invalidJsonException) {
            return new UnableToCreateChirpResponse($invalidJsonException->getMessage());
        }
        return new Response();
    }
}