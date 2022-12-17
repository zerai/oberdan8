<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

use Ramsey\Uuid\Uuid;

class ExportCustomer implements ExportCustomerInterface
{
    private ExportDataRetrieverInterface $datasource;

    private ExportEncoderInterface $encoder;

    public function __construct(ExportDataRetrieverInterface $datasource, ExportEncoderInterface $encoder)
    {
        $this->datasource = $datasource;
        $this->encoder = $encoder;
    }

    /**
     * @throws CouldNotExportDataException
     */
    public function exportAllCustomer(): ExportedFileWrapperInterface
    {
        $data = $this->datasource->getAllCustomerForNewsletter();

        if ($data === []) {
            throw new CouldNotExportDataException();
        }

        $exportedData = $this->encoder->convertData($data);

        return new ExportedFile($this->generateFilename(), $exportedData);
    }

    /**
     * Generate a unique filename for the exported file
     * @return string
     */
    private function generateFilename(): string
    {
        return sprintf('ExportCustomers_%s.csv', Uuid::uuid4()->toString());
    }
}
