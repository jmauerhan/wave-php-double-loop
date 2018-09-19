<?php declare(strict_types=1);

namespace Test\Behavior\Context\Frontend;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Session;
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

    /** @var Session */
    private $session;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }

    /**
     * @Given I write a Chirp with :arg1 or less characters
     */
    public function iWriteAChirpWithOrLessCharacters($maxLength)
    {
        $this->session = $this->getSession('js');
        $this->session->start();
        $this->session->visit('http://local.chirper.com:8080');
        $page            = $this->session->getPage();
        $this->chirpText = $this->faker->text($maxLength);
        $this->author    = $this->faker->userName;
        $page->fillField('chirp', $this->chirpText);
        $page->fillField('author', $this->author);
    }

    /**
     * @When I submit the Chirp
     */
    public function iSubmitTheChirp()
    {
        $page = $this->session->getPage();
        $page->find('xpath', '//button')->click();
        $this->session->wait(1500);
    }

    /**
     * @Then I should see it in my timeline
     */
    public function iShouldSeeItInMyTimeline()
    {
        $firstTimelineItem =
            $this->session->getPage()->find('xpath', "//div[@class='v-list__tile__content']//div");
        Assert::assertEquals($this->chirpText, $firstTimelineItem->getText());
    }


    /**
     * @Given I write a Chirp with more than :minLength characters
     */
    public function iWriteAChirpWithMoreThanCharacters($minLength)
    {
        $this->session = $this->getSession();
        $this->session->start();
        $this->session->visit('http://local.chirper.com:8080');
        $page            = $this->session->getPage();
        $this->chirpText = $this->faker->sentence;
        while (strlen($this->chirpText) <= $minLength) {
            $this->chirpText .= $this->faker->sentence;
        }
        $this->author = $this->faker->userName;
        $page->fillField('chirp', $this->chirpText);
        $page->fillField('author', $this->author);
    }

    /**
     * @Then I should not see it in my timeline
     */
    public function iShouldNotSeeItInMyTimeline()
    {
        $firstTimelineItem = $this->session
            ->getPage()
            ->find('xpath', "//div[@class='v-list__tile__content']//div");
        Assert::assertNotEquals($this->chirpText, $firstTimelineItem->getText());
    }

    /**
     * @Then I should see an error message
     */
    public function iShouldSeeAnErrorMessage()
    {
        $error = $this->session->getPage()->find('css', '.v-alert');
        Assert::assertNotNull($error);
        Assert::assertContains("Sorry, your chirp must be 100 characters or less", $error->getText());
    }
}
