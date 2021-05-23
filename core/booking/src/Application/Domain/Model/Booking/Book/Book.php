<?php

declare(strict_types=1);

namespace Booking\Application\Domain\Model\Booking\Book;

/**
 * null
 * @codeCoverageIgnore
 */
final class Book
{
    private Isbn $isbn;

    private Title $title;

    private Author $author;

    private Volume $volume;

    public function __construct(Isbn $isbn, Title $title, Author $author, Volume $volume)
    {
        $this->isbn = $isbn;
        $this->title = $title;
        $this->author = $author;
        $this->volume = $volume;
    }

    public function isbn(): Isbn
    {
        return $this->isbn;
    }

    public function withIsbn(Isbn $isbn): self
    {
        return new self(
            $isbn,
            $this->title,
            $this->author,
            $this->volume
        );
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function withTitle(Title $title): self
    {
        return new self(
            $this->isbn,
            $title,
            $this->author,
            $this->volume
        );
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function withAuthor(Author $author): self
    {
        return new self(
            $this->isbn,
            $this->title,
            $author,
            $this->volume
        );
    }

    public function volume(): Volume
    {
        return $this->volume;
    }

    public function withVolume(Volume $volume): self
    {
        return new self(
            $this->isbn,
            $this->title,
            $this->author,
            $volume
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['isbn']) || ! \is_string($data['isbn'])) {
            throw new \InvalidArgumentException('Error on "isbn", string expected');
        }

        if (! isset($data['title']) || ! \is_string($data['title'])) {
            throw new \InvalidArgumentException('Error on "title", string expected');
        }

        if (! isset($data['author']) || ! \is_string($data['author'])) {
            throw new \InvalidArgumentException('Error on "author", string expected');
        }

        if (! isset($data['volume']) || ! \is_string($data['volume'])) {
            throw new \InvalidArgumentException('Error on "volume", string expected');
        }

        return new self(
            new Isbn($data['isbn']),
            new Title($data['title']),
            new Author($data['author']),
            new Volume($data['volume']),
        );
    }

    public function toArray(): array
    {
        return [
            'isbn' => $this->isbn->value(),
            'title' => $this->title->value(),
            'author' => $this->author->value(),
            'volume' => $this->volume->value(),
        ];
    }

    public function equals(?Book $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if (! $this->isbn->equals($other->isbn)) {
            return false;
        }

        if (! $this->title->equals($other->title)) {
            return false;
        }

        if (! $this->author->equals($other->author)) {
            return false;
        }

        if (! $this->volume->equals($other->volume)) {
            return false;
        }

        return true;
    }
}
