<?php declare(strict_types=1);

namespace Test\Chirp;

use Chirper\Chirp\Chirp;
use Chirper\Chirp\ChirpPersistence;
use Chirper\Chirp\CreateAction;
use Chirper\Chirp\InvalidJsonException;
use Chirper\Chirp\JsonChirpTransformer;
use Chirper\Chirp\UnableToCreateChirpResponse;
use Chirper\Http\InternalServerErrorResponse;
use Chirper\Persistence\PersistenceDriverException;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Chirper\Http\Request;

class CreateActionTest extends TestCase
{
    /** @var MockObject|JsonChirpTransformer */
    private $transformer;

    /** @var MockObject|ChirpPersistence */
    private $persistence;

    /** @var Generator */
    private $faker;

    public function setUp()
    {
        $this->transformer = $this->createMock(JsonChirpTransformer::class);
        $this->persistence = $this->createMock(ChirpPersistence::class);
        $this->faker       = Factory::create();
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
        $request = new Request('POST', 'chirp', [], "");
        $chirp   = new Chirp($this->faker->uuid,
                             $this->faker->realText(100),
                             $this->faker->userName,
                             new \DateTime());

        $this->transformer->method('toChirp')
                          ->willReturn($chirp);

        $this->persistence->expects($this->once())
                          ->method('save')
                          ->with($chirp);

        $action = new CreateAction($this->transformer, $this->persistence);
        $action->create($request);
    }

    public function testCreateReturnsInternalErrorResponseOnPersistenceException()
    {
        $request = new Request('POST', 'chirp', [], "");

        $exception = new PersistenceDriverException();
        $this->persistence->method('save')
                          ->willThrowException($exception);

        $action   = new CreateAction($this->transformer, $this->persistence);
        $response = $action->create($request);
        $this->assertInstanceOf(InternalServerErrorResponse::class, $response);
    }
//
//    public function testCreateSendsSavedChirpToTransformer()
//    {
//
//    }
//
//    public function testCreateReturnsInternalServerErrorResponseOnTransformerException()
//    {
//    }
//
//    public function testCreateReturnsChirpCreatedResponseOnSuccess()
//    {
//    }
}
