<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

use Booking\Application\ApplicationPort;

interface ExportDataRetrieverInterface extends ApplicationPort
{
    public function getAllCustomerForNewsletter(): array;
}
