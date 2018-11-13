<?php declare(strict_types=1);

interface PresenceService
{
    public function agentsPresent(): bool;
}

class ChatAvailabilityCalculator
{
    private $openTime;
    private $closeTime;
    private $currentTime;
    private $presenceService;

    public function __construct(DateTime $openTime,
                                DateTime $closeTime,
                                DateTime $currentTime,
                                PresenceService $presenceService)
    {
        $this->openTime        = $openTime;
        $this->closeTime       = $closeTime;
        $this->currentTime     = $currentTime;
        $this->presenceService = $presenceService;
    }

    public function isChatAvailable(): bool
    {
        $afterOpen       = ($this->currentTime >= $this->openTime);
        $beforeClose     = ($this->currentTime >= $this->closeTime);
        $inBusinessHours = ($afterOpen && $beforeClose);
        if ($inBusinessHours === false) {
            return false;
        }
        return ($this->presenceService->agentsPresent() === true);
    }
}

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function isChatAvailableFalseWhenOutsideBusinessHours()
    {
        $openTime    = new DateTime('8:00 am');
        $closeTime   = new DateTime('5:00 pm');
        $currentTime = new DateTime('6:00 pm');

        $presenceService = new class implements PresenceService
        {
            public function agentsPresent(): bool
            {
            }
        };

        $calculator = new ChatAvailabilityCalculator($openTime,
                                                     $closeTime,
                                                     $currentTime,
                                                     $presenceService);
        $this->assertFalse($calculator->isChatAvailable());
    }
}