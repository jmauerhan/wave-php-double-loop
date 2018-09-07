<?php declare(strict_types=1);

namespace Test\Unit\Chirp;

use Chirper\Chirp\Chirp;
use Chirper\Chirp\ChirpCreatedResponse;
use Chirper\Chirp\PersistenceDriver;
use Chirper\Chirp\CreateAction;
use Chirper\Json\InvalidJsonException;
use Chirper\Chirp\JsonChirpTransformer;
use Chirper\Chirp\UnableToCreateChirpResponse;
use Chirper\Http\InternalServerErrorResponse;
use Chirper\Persistence\PersistenceDriverException;
use Chirper\Json\TransformerException;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use Chirper\Http\Request;
use Test\TestCase;

class CreateActionTest extends TestCase
{
    /** @var MockObject|JsonChirpTransformer */
    private $transformer;

    /** @var MockObject|PersistenceDriver */
    private $persistence;

    public function setUp()
    {
        $this->transformer = $this->createMock(JsonChirpTransformer::class);
        $this->persistence = $this->createMock(PersistenceDriver::class);
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

        $exception = new InvalidJsonException([]);
        $this->transformer->method('toChirp')
                          ->willThrowException($exception);

        $action   = new CreateAction($this->transformer, $this->persistence);
        $response = $action->create($request);
        $this->assertInstanceOf(UnableToCreateChirpResponse::class, $response);
    }

    /** @group realtext */
    public function testCreateSendsChirpToPersistence()
    {
        $request = new Request('POST', 'chirp', [], "");
        $chirp   = new Chirp($this->faker->uuid,
                             $this->faker->text(100),
                             $this->faker->userName,
                             $this->faker->date('Y-m-d H:i:s'));

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

    /** @group realtext */
    public function testCreateSendsSavedChirpToTransformer()
    {
        $request = new Request('POST', 'chirp', [], "");
        $chirp   = new Chirp($this->faker->uuid,
                             $this->faker->text(100),
                             $this->faker->userName,
                             $this->faker->date('Y-m-d H:i:s'));

        $this->transformer->method('toChirp')
                          ->willReturn($chirp);

        $this->transformer->expects($this->once())
                          ->method('toJson')
                          ->with($chirp);

        $action = new CreateAction($this->transformer, $this->persistence);
        $action->create($request);
    }

    public function testCreateReturnsInternalServerErrorResponseOnTransformerException()
    {
        $request = new Request('POST', 'chirp', [], "");

        $exception = new TransformerException();
        $this->transformer->method('toChirp')
                          ->willThrowException($exception);

        $action   = new CreateAction($this->transformer, $this->persistence);
        $response = $action->create($request);
        $this->assertInstanceOf(InternalServerErrorResponse::class, $response);
    }

    public function testCreateReturnsChirpCreatedResponseOnSuccess()
    {
        $request      = new Request('POST', 'chirp', [], "");
        $jsonResponse = '{"somedata":"json_data"}';
        $this->transformer->method('toJson')
                          ->willReturn($jsonResponse);

        $action   = new CreateAction($this->transformer, $this->persistence);
        $response = $action->create($request);

        $this->assertInstanceOf(ChirpCreatedResponse::class, $response);
        $this->assertEquals($jsonResponse, $response->getBody()->getContents());
    }
}
