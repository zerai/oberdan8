<?php declare(strict_types=1);

namespace Booking\Tests\Application\Domain\UseCase;

use Booking\Application\Domain\UseCase\CouldNotExportDataException;
use Booking\Application\Domain\UseCase\ExportCustomer;
use Booking\Application\Domain\UseCase\ExportDataRetrieverInterface;
use Booking\Application\Domain\UseCase\ExportedFile;
use Booking\Application\Domain\UseCase\ExportEncoderInterface;
use Booking\Infrastructure\Exporter\CsvEncoder;
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
            ->method('getAllCustomerForNewsletter')
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
            ->method('getAllCustomerForNewsletter')
            ->willReturn([
                1 => [
                    'foo' => 'bar',

                ],
            ])
        ;

        $exporterDataConverter->expects(self::once())
            ->method('convertData')
            ->willReturn('');

        $useCase->exportAllCustomer();
    }

    public function testShouldThrowExceptionForEmptyDatasource(): void
    {
        self::expectException(CouldNotExportDataException::class);

        $exporterDataRetriever = self::createMock(ExportDataRetrieverInterface::class);
        $exporterDataConverter = self::createMock(ExportEncoderInterface::class);
        $useCase = new ExportCustomer($exporterDataRetriever, $exporterDataConverter);

        $exporterDataRetriever->expects(self::once())
            ->method('getAllCustomerForNewsletter')
            ->willReturn([]);

        $useCase->exportAllCustomer();
    }

    public function testShouldExportDataInCsv(): void
    {
        $exporterDataRetriever = self::createMock(ExportDataRetrieverInterface::class);
        $exporterDataConverter = new CsvEncoder();
        $useCase = new ExportCustomer($exporterDataRetriever, $exporterDataConverter);

        $exporterDataRetriever->expects(self::once())
            ->method('getAllCustomerForNewsletter')
            ->willReturn([
                [
                    'foo' => 'foo',
                    'bar' => 'bar',
                ],
            ]);

        $result = $useCase->exportAllCustomer();

        self::assertInstanceOf(ExportedFile::class, $result);
    }
}
