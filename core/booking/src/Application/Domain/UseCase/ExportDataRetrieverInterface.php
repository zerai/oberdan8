<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

interface ExportDataRetrieverInterface
{
    public function getData(): array;
}
