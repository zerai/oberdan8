<?php declare(strict_types=1);


namespace Booking\Tests\Unit\Domain\Book;

use Booking\Application\Domain\Model\Booking\Book\Author;
use Booking\Application\Domain\Model\Booking\Book\Book;
use Booking\Application\Domain\Model\Booking\Book\Isbn;
use Booking\Application\Domain\Model\Booking\Book\Title;
use Booking\Application\Domain\Model\Booking\Book\Volume;
use PHPUnit\Framework\TestCase;

/**
 * Class BookTest
 * @covers \Booking\Application\Domain\Model\Booking\Book\Book
 * @package Booking\Tests\Unit\Domain\Book
 */
class BookTest extends TestCase
{
    /** @test */
    public function canBeCreated(): void
    {
        $sut = new Book(
            new Isbn('irrelevant isbn'),
            new Title('irrelevant title'),
            new Author('irrelevant author'),
            new Volume('secondo')
        );

        self::assertEquals('irrelevant isbn', $sut->isbn()->value());
        self::assertEquals('irrelevant title', $sut->title()->value());
        self::assertEquals('irrelevant author', $sut->author()->value());
        self::assertEquals('secondo', $sut->volume()->value());
    }
}
