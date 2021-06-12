<?php declare(strict_types=1);

namespace Booking\Tests\Unit\Infrastructure;

use Booking\Infrastructure\BookingConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Booking\Infrastructure\BookingConfiguration
 */
class BookingConfigurationTest extends TestCase
{
    private const BOOKING_EMAIL_SENDER_ADDRESS = 'irrelevant.sender@example.com';

    private const BOOKING_EMAIL_SENDER_NAME = 'irrelevant.sender';

    private const BACKOFFICE_EMAIL_RETRIEVER_ADDRESS = 'irrelevant.retriever@example.com';

    private const BACKOFFICE_EMAIL_RETRIEVER__NAME = 'irrelevant.retriever';

    /**
     * @test
     * @covers \Booking\Infrastructure\BookingEmailSender
     */
    public function shouldCreateABookingEmailSender(): void
    {
        $conf = new BookingConfiguration(
            self::BOOKING_EMAIL_SENDER_ADDRESS,
            self::BOOKING_EMAIL_SENDER_NAME,
            self::BACKOFFICE_EMAIL_RETRIEVER_ADDRESS,
            self::BACKOFFICE_EMAIL_RETRIEVER__NAME
        );

        $bookingSender = $conf->emailSender();

        self::assertSame(self::BOOKING_EMAIL_SENDER_ADDRESS, $bookingSender->address());
        self::assertSame(self::BOOKING_EMAIL_SENDER_NAME, $bookingSender->name());
    }

    /**
     * @test
     * @covers \Booking\Infrastructure\BackofficeEmailRetriever
     */
    public function shouldCreateABackofficeEmailRetriever(): void
    {
        $conf = new BookingConfiguration(
            self::BOOKING_EMAIL_SENDER_ADDRESS,
            self::BOOKING_EMAIL_SENDER_NAME,
            self::BACKOFFICE_EMAIL_RETRIEVER_ADDRESS,
            self::BACKOFFICE_EMAIL_RETRIEVER__NAME
        );

        $backofficeRetriever = $conf->backofficeEmailRetriever();

        self::assertSame(self::BACKOFFICE_EMAIL_RETRIEVER_ADDRESS, $backofficeRetriever->address());
        self::assertSame(self::BACKOFFICE_EMAIL_RETRIEVER__NAME, $backofficeRetriever->name());
    }
}
