<?php declare(strict_types=1);

namespace Booking\Tests\Application\Domain\UseCase;

use Booking\Application\Domain\UseCase\CouldNotExportDataException;
use Booking\Application\Domain\UseCase\ExportCustomer;
use Booking\Application\Domain\UseCase\ExportDataRetrieverInterface;
use Booking\Application\Domain\UseCase\ExportEncoderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Booking\Application\Domain\UseCase\ExportCustomer
 */
class ExportCustomerTest extends TestCase
{
    public function testShouldFetchDataFromPersistence(): void
    {
        $exporterDataRetriever = self::createMock(ExportDataRetrieverInterface::class);
        $exporterDataConverter = self::createMock(ExportEncoderInterface::class);
        $useCase = new ExportCustomer($exporterDataRetriever, $exporterDataConverter);

        $exporterDataRetriever->expects(self::once())
            ->method('getData')
            ->willReturn([
                1 => [
                    'foo' => 'bar',

                ],
            ])
            ;

        $useCase->exportAllCustomer();
    }

    public function testShouldTransformDataForExport(): void
    {
        $exporterDataRetriever = self::createMock(ExportDataRetrieverInterface::class);
        $exporterDataConverter = self::createMock(ExportEncoderInterface::class);
        $useCase = new ExportCustomer($exporterDataRetriever, $exporterDataConverter);

        $exporterDataRetriever->expects(self::once())
            ->method('getData')
            ->willReturn([
                1 => [
                    'foo' => 'bar',

                ],
            ])
        ;

        $exporterDataConverter->expects(self::once())
            ->method('convertData')
            ->willReturn(new \stdClass());

        $useCase->exportAllCustomer();
    }

    public function testShouldThrowExceptionForEmptyDatasource(): void
    {
        self::expectException(CouldNotExportDataException::class);

        $exporterDataRetriever = self::createMock(ExportDataRetrieverInterface::class);
        $exporterDataConverter = self::createMock(ExportEncoderInterface::class);
        $useCase = new ExportCustomer($exporterDataRetriever, $exporterDataConverter);

        $exporterDataRetriever->expects(self::once())
            ->method('getData')
            ->willReturn([]);

        $useCase->exportAllCustomer();
    }
}
