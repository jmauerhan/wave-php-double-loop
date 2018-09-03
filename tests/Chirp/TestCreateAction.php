<?php declare(strict_types=1);

namespace Test\Chirp;

use PHPUnit\Framework\TestCase;

class TestCreateAction extends TestCase
{
    private $jsonChirpTransformer;
    private $chirpPersistence;

    public function setUp()
    {

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
