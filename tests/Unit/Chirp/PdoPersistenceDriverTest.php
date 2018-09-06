<?php declare(strict_types=1);

namespace Test\Unit\Chirp;

use Chirper\Chirp\Chirp;
use Chirper\Persistence\PersistenceDriverException;
use Chirper\Chirp\PdoPersistenceDriver;
use PHPUnit\Framework\MockObject\MockObject;
use Test\TestCase;

class PdoPersistenceDriverTest extends TestCase
{
    /** @var Chirp */
    private $chirp;

    /** @var MockObject|\PDO */
    private $pdo;

    public function setUp()
    {
        $this->pdo = $this->createMock(\PDO::class);

        $uuid        = $this->faker->uuid;
        $text        = $this->faker->realText(50);
        $author      = $this->faker->userName;
        $date        = $this->faker->date('Y-m-d H:i:s');
        $this->chirp = new Chirp($uuid, $text, $author, $date);
        parent::setUp();
    }

    public function testSavePreparesStatement()
    {
        $sql = "INSERT INTO chirp(id, chirp_text, author, created_at) " .
               "VALUES(:id, :chirp_text, :author, :created_at)";

        $statement = $this->createMock(\PDOStatement::class);
        $this->pdo->expects($this->once())
                  ->method('prepare')
                  ->with($sql)
                  ->willReturn($statement);

        $driver = new PdoPersistenceDriver($this->pdo);
        $driver->save($this->chirp);
    }

    public function testSaveThrowsExceptionWhenPrepareThrowsException()
    {
        $this->expectException(PersistenceDriverException::class);

        $this->pdo->method('prepare')
                  ->willThrowException(new \PDOException());

        $driver = new PdoPersistenceDriver($this->pdo);
        $driver->save($this->chirp);
    }

    public function testSaveExecutesStatement()
    {
        $uuid   = $this->faker->uuid;
        $text   = $this->faker->realText(50);
        $author = $this->faker->userName;
        $date   = $this->faker->date('Y-m-d H:i:s');
        $chirp  = new Chirp($uuid, $text, $author, $date);
        $params = [
            'id'         => $uuid,
            'chirp_text' => $text,
            'author'     => $author,
            'created_at' => $date
        ];

        $statement = $this->createMock(\PDOStatement::class);
        $statement->expects($this->once())
                  ->method('execute')
                  ->with($params);
        $this->pdo->method('prepare')
                  ->willReturn($statement);

        $driver = new PdoPersistenceDriver($this->pdo);
        $driver->save($chirp);
    }

    public function testSaveThrowsExceptionWhenExecuteReturnsFalse()
    {
        $this->expectException(PersistenceDriverException::class);
        $statement = $this->createMock(\PDOStatement::class);
        $statement->method('execute')
                  ->willReturn(false);
        $statement->method('errorInfo')
                  ->willReturn(['errors']);
        $this->pdo->method('prepare')
                  ->willReturn($statement);
        $driver = new PdoPersistenceDriver($this->pdo);
        $driver->save($this->chirp);

    }

    public function testSaveReturnsTrueWhenChirpInserted()
    {
        $statement = $this->createMock(\PDOStatement::class);
        $this->pdo->method('prepare')
                  ->willReturn($statement);
        $driver = new PdoPersistenceDriver($this->pdo);
        $this->assertTrue($driver->save($this->chirp));
    }

}
