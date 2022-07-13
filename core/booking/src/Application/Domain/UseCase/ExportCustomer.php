<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

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
    public function exportAllCustomer(): object
    {
        $data = $this->datasource->getData();

        if ($data === []) {
            throw new CouldNotExportDataException();
        }

        return $this->encoder->convertData($data);
    }
}
