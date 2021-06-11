<?php declare(strict_types=1);


namespace Booking\Infrastructure;

class BookingConfiguration
{
    private string $mailerSender;

    private string $mailerSenderAs;

    private string $backofficeRetriever;

    private string $backofficeRetrieverAs;

    /**
     * BookingConfiguration constructor.
     * @param string $mailerSender
     * @param string $mailerSenderAs
     * @param string $backofficeRetriever
     * @param string $backofficeRetrieverAs
     */
    public function __construct(string $mailerSender, string $mailerSenderAs, string $backofficeRetriever, string $backofficeRetrieverAs)
    {
        $this->mailerSender = $mailerSender;
        $this->mailerSenderAs = $mailerSenderAs;
        $this->backofficeRetriever = $backofficeRetriever;
        $this->backofficeRetrieverAs = $backofficeRetrieverAs;
    }

    public function emailSender(): BookingEmailSender
    {
        return BookingEmailSender::fromData($this->mailerSender, $this->mailerSenderAs);
    }

    public function backofficeEmailRetriever(): BackofficeEmailRetriever
    {
        return BackofficeEmailRetriever::fromData($this->backofficeRetriever, $this->backofficeRetrieverAs);
    }
}
