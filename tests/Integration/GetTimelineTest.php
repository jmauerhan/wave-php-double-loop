<?php declare(strict_types=1);

namespace Test\Integration;

use GuzzleHttp\Client;
use Test\Integration\TestCase;

class GetTimelineTest extends TestCase
{
    public function testTimelineReturnsAllChirps()
    {
        /**
         * Load the system up with some chirps to ensure there is data
         */
        $count = 3;
        $sql   = "INSERT INTO chirp(id, chirp_text, author, created_at) 
                VALUES(:id, :chirp_text, :author, :created_at)";
        $stmt  = $this->pdo->prepare($sql);
        for ($i = 0; $i < $count; $i++) {
            $values = [
                'id'         => $this->faker->uuid,
                'chirp_text' => $this->faker->sentence,
                'author'     => $this->faker->userName,
                'created_at' => $this->faker->date('Y-m-d H:i:s')
            ];
            $stmt->execute($values);
        }

        /**
         * Hit the API endpoint
         */
        $guzzle       = new Client(['base_uri' => $this->host]);
        $response     = $guzzle->get('');
        $responseJson = $response->getBody()->getContents();
        $this->assertJson($responseJson);

        /**
         * Get the created at dates, and ensure they're in desc order
         */
        $responseObject = json_decode($responseJson);
        $data           = $responseObject->data;
        $createdAtDates = array_column(array_column($data, 'attributes'), 'created_at');
        $expected       = $createdAtDates;
        rsort($expected);
        $this->assertEquals($expected, $createdAtDates);
    }
}