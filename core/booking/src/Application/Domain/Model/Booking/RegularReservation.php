<?php

declare(strict_types=1);

namespace Booking\Application\Domain\Model\Booking;

use Booking\Application\Domain\Model\Booking\Book\Book;
use Booking\Application\Domain\Model\Booking\Client\Client;
use InvalidArgumentException;

/**
 * null
 * @codeCoverageIgnore
 */
final class RegularReservation
{
    private ReservationId $id;

    private Client $client;

    /** @return list<Book> */
    private array $books;

    /**
     * @param list<Book> $books
     */
    public function __construct(ReservationId $id, Client $client, array $books)
    {
        $this->id = $id;
        $this->client = $client;
        $this->books = $books;
    }

    public function id(): ReservationId
    {
        return $this->id;
    }

    public function withId(ReservationId $id): self
    {
        return new self(
            $id,
            $this->client,
            $this->books
        );
    }

    public function client(): Client
    {
        return $this->client;
    }

    public function withClient(Client $client): self
    {
        return new self(
            $this->id,
            $client,
            $this->books
        );
    }

    /**
     * @return list<Book>
     */
    public function books(): array
    {
        return $this->books;
    }

    /**
     * @param list<Book> $books
     */
    public function withBooks(array $books): self
    {
        return new self(
            $this->id,
            $this->client,
            $books
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['id']) || ! \is_string($data['id'])) {
            throw new InvalidArgumentException('Error on "id", string expected');
        }

        if (! isset($data['client']) || ! \is_array($data['client'])) {
            throw new InvalidArgumentException('Error on "client", array expected');
        }

        if (! isset($data['books']) || ! \is_array($data['books'])) {
            throw new InvalidArgumentException('Error on "books": array expected');
        }

        return new self(
            ReservationId::fromString($data['id']),
            Client::fromArray($data['client']),
            array_map(fn ($e) => Book::fromArray($e), $data['books']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'client' => $this->client->toArray(),
            'books' => array_map(fn (Book $e) => $e->toArray(), $this->books),
        ];
    }

    public function equals(?RegularReservation $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if (! $this->id->equals($other->id)) {
            return false;
        }

        if (! $this->client->equals($other->client)) {
            return false;
        }

        if (\count($this->books) !== \count($other->books)) {
            return false;
        }

        foreach ($this->books as $k => $v) {
            if (! $v->equals($other->books[$k])) {
                return false;
            }
        }

        return true;
    }
}
