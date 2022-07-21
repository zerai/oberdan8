<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

interface ExportEncoderInterface
{
    public function convertData(array $data): string;
}
