<?php

namespace Test\Behavior\Context\Frontend;

use Behat\MinkExtension\Context\MinkContext;
use Faker;
use PHPUnit\Framework\Assert;


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
        $page            = $this->getSession()->getPage();
        $this->chirpText = $this->faker->text($maxLength);
        $this->author    = $this->faker->userName;
        $page->fillField('chirp', $this->chirpText);
        $page->fillField('author', $this->author);
        file_put_contents(time() . '.png', $this->getSession()->getScreenshot());
    }

    /**
     * @When I publish the Chirp
     */
    public function iPublishTheChirp()
    {
        $page = $this->getSession()->getPage();
        $page->find('xpath', '//button')->click();
        $this->getSession()->wait(1000);
        file_put_contents(time() . '.png', $this->getSession()->getScreenshot());
    }

    /**
     * @Then I should see it in my timeline
     */
    public function iShouldSeeItInMyTimeline()
    {
        file_put_contents(time() . '.png', $this->getSession()->getScreenshot());
        $firstTimelineItem =
            $this->getSession()->getPage()->find('xpath', "//div[@class='v-list__tile__content']//div");
        Assert::assertEquals($this->chirpText, $firstTimelineItem->getText());
    }
}
