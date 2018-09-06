<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\InternalServerErrorResponse;
use Chirper\Http\Response;
use Chirper\Json\TransformerException;
use Chirper\Persistence\PersistenceDriverException;

class GetTimelineAction
{
    /** @var JsonChirpTransformer */
    private $transformer;

    /** @var PersistenceDriver */
    private $persistence;

    public function __construct(JsonChirpTransformer $transformer, PersistenceDriver $persistence)
    {
        $this->transformer = $transformer;
        $this->persistence = $persistence;
    }

    public function getAll(): Response
    {
        try {

            $chirps = $this->persistence->getAll();
            $json   = $this->transformer->collectionToJson($chirps);
            return new TimelineResponse($json);
        } catch (PersistenceDriverException $persistenceDriverException) {
            return new InternalServerErrorResponse($persistenceDriverException->getMessage());
        } catch (TransformerException $transformerException) {
            return new InternalServerErrorResponse($transformerException->getMessage());
        }
    }
}