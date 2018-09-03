<?php declare(strict_types=1);

namespace Test\Chirp;

use Chirper\Chirp\ChirpPersistence;
use Chirper\Chirp\CreateAction;
use Chirper\Chirp\InvalidJsonException;
use Chirper\Chirp\JsonChirpTransformer;
use Chirper\Chirp\UnableToCreateChirpResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Chirper\Http\Request;

class CreateActionTest extends TestCase
{
    /** @var MockObject|JsonChirpTransformer */
    private $transformer;

    /** @var MockObject|ChirpPersistence */
    private $persistence;

    public function setUp()
    {
        $this->transformer = $this->createMock(JsonChirpTransformer::class);
        $this->persistence = $this->createMock(ChirpPersistence::class);
        parent::setUp();
    }

    public function testCreateSendsRequestToTransformer()
    {
        $json    = '{"data":"some data"}';
        $request = new Request('POST', 'chirp', [], $json);

        $this->transformer->expects($this->once())
                          ->method('toChirp')
                          ->with($json);

        $action = new CreateAction($this->transformer, $this->persistence);
        $action->create($request);
    }

    public function testCreateReturnsInvalidChirpResponseOnTransformerException()
    {
        $json    = '{"data":"some data"}';
        $request = new Request('POST', 'chirp', [], $json);

        $exception = new InvalidJsonException("Missing id field");
        $this->transformer->method('toChirp')
                          ->willThrowException($exception);

        $action   = new CreateAction($this->transformer, $this->persistence);
        $response = $action->create($request);
        $this->assertInstanceOf(UnableToCreateChirpResponse::class, $response);
    }

    public function testCreateSendsChirpToPersistence()
    {

    }

    public function testCreateReturnsInternalErrorResponseOnPersistenceException()
    {
    }

    public function testCreateSendsSavedChirpToTransformer()
    {

    }

    public function testCreateReturnsInternalServerErrorResponseOnTransformerException()
    {
    }

    public function testCreateReturnsChirpCreatedResponseOnSuccess()
    {
    }
}
