<?php declare(strict_types=1);

namespace Chirper\Chirp;

class Chirp
{
    /** @var string */
    private $id;

    /** @var string */
    private $text;

    /** @var string */
    private $author;

    /** @var string */
    private $createdAt;

    public function __construct(string $id, string $text, string $author, string $createdAt)
    {
        $this->id        = $id;
        $this->text      = $text;
        $this->author    = $author;
        $this->createdAt = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}