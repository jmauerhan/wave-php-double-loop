<?php

namespace Test\Behavior\Context\Frontend;

use Behat\MinkExtension\Context\MinkContext;
use Faker;


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
        $this->getSession()->start();
        $this->getSession()->visit('http://localhost:3000');
        var_dump($this->getSession()->getStatusCode());
        var_dump($this->getSession()->getPage()->getContent());
        $ss = $this->getSession()->getScreenshot();
        file_put_contents(time() . '.png', $ss);

    }

    /**
     * @When I publish the Chirp
     */
    public function iPublishTheChirp()
    {
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
