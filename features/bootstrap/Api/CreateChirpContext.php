<?php

namespace Test\Behavior\Context\Api;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

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

    /** @var ClientException */
    private $exception;

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
     * @When I submit the Chirp
     */
    public function iSubmitTheChirp()
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
        try {
            $this->httpClient->post('chirp', ['json' => $obj]);
        } catch (ClientException $exception) {
            $this->exception = $exception;
        }
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
     * @Given I write a Chirp with more than :minLength characters
     */
    public function iWriteAChirpWithMoreThanCharacters($minLength)
    {
        $this->chirpText = $this->faker->sentence;
        while (strlen($this->chirpText) <= $minLength) {
            $this->chirpText .= $this->faker->sentence;
        }
    }

    /**
     * @Then I should not see it in my timeline
     */
    public function iShouldNotSeeItInMyTimeline()
    {
        $response  = $this->httpClient->get('');
        $json      = $response->getBody()->getContents();
        $chirpData = json_decode($json);
        $chirps    = $chirpData->data;
        foreach ($chirps AS $chirp) {
            if ($chirp->attributes->text == $this->chirpText && $chirp->id == $this->uuid) {
                throw new \Exception("Chirp with text '{$this->chirpText}' and was found");
            }
        }
    }

    /**
     * @Then I should see an error message
     */
    public function iShouldSeeAnErrorMessage()
    {
        $responseData    = $this->exception->getResponse()->getBody()->getContents();
        $responseObjData = json_decode($responseData);
        $errors          = $responseObjData->errors;
        foreach ($errors AS $error) {
            if ($error->source->pointer == "data.attributes.text" &&
                $error->title == "Data.attributes.text must not exceed 100 characters") {
                return true;
            }
        }
        throw new \Exception("Error message not found");
    }
}
