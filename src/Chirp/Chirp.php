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

    /** @var \DateTime */
    private $createdAt;

    public function __construct(string $id, string $text, string $author, \DateTime $createdAt)
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
}