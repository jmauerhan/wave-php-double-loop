<?php declare(strict_types=1);

namespace Test;

use Faker\Factory;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \Generator */
    protected $faker;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create();
    }
}