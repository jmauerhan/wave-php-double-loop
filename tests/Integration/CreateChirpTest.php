<?php declare(strict_types=1);

namespace Test\Integration\Chirp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Ramsey\Uuid\Uuid;
use Test\Integration\TestCase;

class CreateChirpTest extends TestCase
{
    public function testValidPostReturnsSuccessfulResponse()
    {
        $attributes = (object)[
            'text'   => $this->faker->text(50),
            'author' => $this->faker->userName
        ];
        $data       = (object)[
            'type'       => 'chirp',
            'id'         => Uuid::uuid4(),
            'attributes' => $attributes
        ];
        $object     = (object)['data' => $data];
        $payload    = json_encode($object);

        $guzzle = new Client(['base_uri' => $this->host]);
        $opt    = ['body' => $payload];

        $response = $guzzle->post('chirp', $opt);
        $this->assertEquals(201, $response->getStatusCode());
        $responseJson = $response->getBody()->getContents();
        $this->assertJson($responseJson);
        $responseObject = json_decode($responseJson);
        $this->assertObjectHasAttribute('data', $responseObject);
        $this->assertObjectHasAttribute('attributes', $responseObject->data);
        $this->assertObjectHasAttribute('created_at', $responseObject->data->attributes);
        $this->assertNotNull($responseObject->data->attributes->created_at);

        unset($responseObject->data->attributes->created_at);
        $this->assertEquals($data, $responseObject->data);
    }

    public function testInvalidPostReturnsErrorResponse()
    {
        $payload = "{}";

        $guzzle = new Client(['base_uri' => $this->host]);
        $opt    = ['body' => $payload];

        try {
            $guzzle->post('chirp', $opt);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $this->assertEquals(409, $response->getStatusCode());
            $responseJson = $response->getBody()->getContents();
            $this->assertJson($responseJson);
            $responseObject = json_decode($responseJson);
            $this->assertObjectHasAttribute('errors', $responseObject);
            $this->assertCount(10, $responseObject->errors);
        }

    }
}