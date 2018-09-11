<?php

namespace Test\Behavior\Context\Frontend;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Faker;
use GuzzleHttp\Client;

class CreateChirpContext extends MinkContext
{
    /** @var Faker\Generator */
    private $faker;

    /** @var string */
    private $chirpText;

    /** @var string */
    private $uuid;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }

    /**
     * @Given I write a Chirp with :arg1 or less characters
     */
    public function iWriteAChirpWithOrLessCharacters($maxLength)
    {
        $this->getSession()->visit('http://localhost:3000');
        $this->chirpText = $this->faker->text($maxLength);
        $page            = $this->getSession()->getPage();
        $page->fillField("chirp", 'testing');
        $page->fillField('author', 'me');

    }

    /**
     * @When I publish the Chirp
     */
    public function iPublishTheChirp()
    {
        $page = $this->getSession()->getPage();
        $page->pressButton('CHIRP!');
//        $this->uuid = $this->faker->uuid;
//        $author     = $this->faker->userName;
//        $obj        = (object)[
//            'data' => (object)[
//                'type'       => 'chirp',
//                'id'         => $this->uuid,
//                'attributes' => (object)[
//                    'text'   => $this->chirpText,
//                    'author' => $author
//                ]
//            ]
//        ];
//        $this->httpClient->post('chirp', ['json' => $obj]);
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
}
