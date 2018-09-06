<?php declare(strict_types=1);

namespace Test\Unit\Chirp;

use Chirper\Chirp\ChirpCollection;
use Chirper\Chirp\GetTimelineAction;
use Chirper\Chirp\TimelineResponse;
use Chirper\Http\InternalServerErrorResponse;
use Chirper\Json\TransformerException;
use Chirper\Persistence\PersistenceDriverException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Chirper\Chirp\JsonChirpTransformer;
use Chirper\Chirp\PersistenceDriver;

class GetTimelineActionTest extends TestCase
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

    public function testGetAllGetsChirpsFromPersistenceDriver()
    {
        $this->persistence->expects($this->once())
                          ->method('getAll');
        $action = new GetTimelineAction($this->transformer, $this->persistence);
        $action->getAll();
    }

    public function testGetAllReturnsInternalErrorResponseOnPersistenceException()
    {
        $exception = new PersistenceDriverException();
        $this->persistence->method('getAll')
                          ->willThrowException($exception);
        $action   = new GetTimelineAction($this->transformer, $this->persistence);
        $response = $action->getAll();
        $this->assertInstanceOf(InternalServerErrorResponse::class, $response);
    }

    public function testGetAllTransformsChirpsToJson()
    {
        $collection = new ChirpCollection();
        $this->persistence->method('getAll')
                          ->willReturn($collection);
        $this->transformer->expects($this->once())
                          ->method('collectionToJson')
                          ->with($collection);
        $action = new GetTimelineAction($this->transformer, $this->persistence);
        $action->getAll();
    }

    public function testGetAllReturnsInternalErrorResponseOnTransformerException()
    {
        $exception = new TransformerException();
        $this->transformer->method('collectionToJson')
                          ->willThrowException($exception);
        $action   = new GetTimelineAction($this->transformer, $this->persistence);
        $response = $action->getAll();
        $this->assertInstanceOf(InternalServerErrorResponse::class, $response);
    }

    public function testGetAllReturnsTimelineResponseWithChirps()
    {
        $collection = new ChirpCollection();
        $this->persistence->method('getAll')
                          ->willReturn($collection);
        $this->transformer->expects($this->once())
                          ->method('collectionToJson')
                          ->with($collection);
        $action   = new GetTimelineAction($this->transformer, $this->persistence);
        $response = $action->getAll();
        $this->assertInstanceOf(TimelineResponse::class, $response);
    }
}
