<?php

namespace Test\Integration\Chirp;

use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;
use Test\TestCase;

class CreateChirpTest extends TestCase
{
    private $host = 'http://api';

    public function testValidPostReturnsSuccessfulResponse()
    {
        $attributes = (object)[
            'text'   => $this->faker->realText(50),
            'author' => $this->faker->userName
        ];
        $data       = (object)[
            'type'       => 'chirp',
            'id'         => Uuid::uuid4(),
            'attributes' => $attributes
        ];
        $object     = (object)['data' => $data];
        $payload    = json_encode($object);

        $expected        = $object;
        $expected->data
            ->attributes
            ->created_at = (new \DateTime())->format('Y-m-d H:i:s');
        $expected        = json_encode($expected);

        $guzzle = new Client(['base_uri' => $this->host]);
        $opt    = ['body' => $payload];

        $response = $guzzle->post('chirp', $opt);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            $expected,
            $response->getBody()->getContents());

    }
}