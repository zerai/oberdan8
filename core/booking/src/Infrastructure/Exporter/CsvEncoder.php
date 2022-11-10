<?php declare(strict_types=1);

namespace Booking\Infrastructure\Exporter;

use Booking\Application\Domain\UseCase\ExportEncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder as SymfonyCsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CsvEncoder implements ExportEncoderInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer = null)
    {
        $this->serializer = $serializer ?? new Serializer([new ObjectNormalizer()], [new SymfonyCsvEncoder()]);
    }

    public function convertData(array $data): string
    {
        return $this->serializer->serialize($data, 'csv');
    }
}
