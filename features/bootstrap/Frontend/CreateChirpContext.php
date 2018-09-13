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
    private $author;

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
        $this->getSession()->visit("http://local.chirper.com:8080");
        $ss = $this->getSession()->getScreenshot();
        file_put_contents(time() . '.png', $ss);
        $page            = $this->getSession()->getPage();
        $this->chirpText = $this->faker->text($maxLength);
        $this->author    = $this->faker->userName;
        $page->fillField('chirp', $this->chirpText);
        $page->fillField('author', $this->author);
    }

    /**
     * @When I publish the Chirp
     */
    public function iPublishTheChirp()
    {
        $page = $this->getSession()->getPage();
        $page->find('xpath', '//button')->click();
        $this->getSession()->wait(2000);
    }

    /**
     * @Then I should see it in my timeline
     */
    public function iShouldSeeItInMyTimeline()
    {
        $ss = $this->getSession()->getScreenshot();
        file_put_contents(time() . '.png', $ss);
    }
}
