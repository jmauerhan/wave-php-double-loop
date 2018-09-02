<?php

namespace Test\Behavior\Context\Api;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;

class CreateChirpContext implements Context
{

    /**
     * @Given I write a Chirp with :arg1 or less characters
     */
    public function iWriteAChirpWithOrLessCharacters($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I post the Chirp
     */
    public function iPostTheChirp()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see it in my timeline
     */
    public function iShouldSeeItInMyTimeline()
    {
        throw new PendingException();
    }
}
