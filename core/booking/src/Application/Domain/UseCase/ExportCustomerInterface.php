<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

use Booking\Application\ApplicationPort;

interface ExportCustomerInterface extends ApplicationPort
{
    /**
     * @throws CouldNotExportDataException
     */
    public function exportAllCustomer(): ExportedFileWrapperInterface;
}
