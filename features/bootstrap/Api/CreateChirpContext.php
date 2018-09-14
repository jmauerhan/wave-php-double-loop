<?php

namespace Test\Behavior\Context\Api;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Faker;
use GuzzleHttp\Client;

class CreateChirpContext implements Context
{
    /** @var Faker\Generator */
    private $faker;

    /** @var Client */
    private $httpClient;

    /** @var string */
    private $chirpText;

    /** @var string */
    private $uuid;

    public function __construct()
    {
        $this->faker      = Faker\Factory::create();
        $clientOpt        = [
            'base_uri' => 'http://localhost:3001',
        ];
        $client           = new Client($clientOpt);
        $this->httpClient = $client;
    }

    /**
     * @Given I write a Chirp with :arg1 or less characters
     */
    public function iWriteAChirpWithOrLessCharacters($maxLength)
    {
        $this->chirpText = $this->faker->text($maxLength);
    }

    /**
     * @When I publish the Chirp
     */
    public function iPublishTheChirp()
    {
        $this->uuid = $this->faker->uuid;
        $author     = $this->faker->userName;
        $obj        = (object)[
            'data' => (object)[
                'type'       => 'chirp',
                'id'         => $this->uuid,
                'attributes' => (object)[
                    'text'   => $this->chirpText,
                    'author' => $author
                ]
            ]
        ];
        $this->httpClient->post('chirp', ['json' => $obj]);
    }

    /**
     * @Then I should see it in my timeline
     */
    public function iShouldSeeItInMyTimeline()
    {
        $response  = $this->httpClient->get('');
        $json      = $response->getBody()->getContents();
        $chirpData = json_decode($json);
        $chirps    = $chirpData->data;
        foreach ($chirps AS $chirp) {
            if ($chirp->attributes->text == $this->chirpText && $chirp->id == $this->uuid) {
                return true;
            }
        }
        throw new \Exception("Chirp with text '{$this->chirpText}' and UUID: {$this->uuid} not found");
    }

    /**
     * @When I submit the Chirp
     */
    public function iSubmitTheChirp()
    {
        throw new PendingException();
    }

    /**
     * @Given I write a Chirp with more than :arg1 characters
     */
    public function iWriteAChirpWithMoreThanCharacters($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should not see it in my timeline
     */
    public function iShouldNotSeeItInMyTimeline()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see an error message
     */
    public function iShouldSeeAnErrorMessage()
    {
        throw new PendingException();
    }
}
