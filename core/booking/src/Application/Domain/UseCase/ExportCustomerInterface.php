<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

interface ExportCustomerInterface
{
    /**
     * @throws CouldNotExportDataException
     */
    public function exportAllCustomer(): object;
}
