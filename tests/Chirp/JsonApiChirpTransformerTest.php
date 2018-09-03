<?php declare(strict_types=1);

namespace Test\Chirp;

use Chirper\Chirp\Chirp;
use Chirper\Chirp\JsonApiChirpTransformer;
use Test\TestCase;

class JsonApiChirpTransformerTest extends TestCase
{
    public function testToChirpReturnsChirpWithPropertiesFromJson()
    {
        $uuid      = $this->faker->uuid;
        $chirpText = $this->faker->realText(50);
        $author    = $this->faker->userName;

        $now = new \DateTime();

        $attributes = (object)[
            "text"   => $chirpText,
            "author" => $author
        ];
        $data       = (object)[
            "type"       => "chirp",
            "id"         => $uuid,
            "attributes" => $attributes
        ];

        $json = json_encode((object)['data' => $data]);

        $expectedChirp = new Chirp($uuid, $chirpText, $author, $now);

        $transformer = new JsonApiChirpTransformer();
        $chirp       = $transformer->toChirp($json);

        $this->assertEquals($expectedChirp, $chirp);
    }
}
