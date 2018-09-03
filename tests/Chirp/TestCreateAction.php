<?php declare(strict_types=1);

namespace Test\Chirp;

use Chirper\Chirp\ChirpPersistence;
use Chirper\Chirp\JsonChirpTransformer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TestCreateAction extends TestCase
{
    /** @var MockObject|JsonChirpTransformer */
    private $jsonChirpTransformer;

    /** @var MockObject|ChirpPersistence */
    private $chirpPersistence;

    public function setUp()
    {
        $this->jsonChirpTransformer = $this->createMock(JsonChirpTransformer::class);
        $this->chirpPersistence     = $this->createMock(ChirpPersistence::class);
        parent::setUp();
    }

    public function testCreateSendsRequestToTransformer()
    {

    }

    public function testCreateReturnsInvalidChirpResponseOnTransformerException()
    {

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
