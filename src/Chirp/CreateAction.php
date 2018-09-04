<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\InternalServerErrorResponse;
use Chirper\Http\Request;
use Chirper\Http\Response;
use Chirper\Persistence\PersistenceDriverException;
use Chirper\Json\InvalidJsonException;
use Chirper\Json\TransformerException;

class CreateAction
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

    public function create(Request $request): Response
    {
        $json = $request->getBody()->getContents();
        try {
            $chirp = $this->transformer->toChirp($json);
            $this->persistence->save($chirp);
            $jsonResponse = $this->transformer->toJson($chirp);
            return new ChirpCreatedResponse($jsonResponse);
        } catch (InvalidJsonException $invalidJsonException) {
            return new UnableToCreateChirpResponse($invalidJsonException->getMessage());
        } catch (PersistenceDriverException $persistenceDriverException) {
            return new InternalServerErrorResponse($persistenceDriverException->getMessage());
        } catch (TransformerException $transformerException) {
            return new InternalServerErrorResponse($transformerException->getMessage());
        }
    }
}