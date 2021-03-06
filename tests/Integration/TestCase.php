<?php

namespace Test\Integration;

use PDO;

class TestCase extends \Test\TestCase
{
    /** @var string */
    protected $host = 'http://api.chirper.com:3001';

    /** @var PDO */
    protected $pdo;

    public function setUp()
    {
        $dsn       = 'pgsql:dbname=chirper;host=db';
        $dbUser    = 'postgres';
        $dbPass    = 'postgres';
        $this->pdo = new PDO($dsn, $dbUser, $dbPass);
        parent::setUp(); // TODO: Change the autogenerated stub
    }
}