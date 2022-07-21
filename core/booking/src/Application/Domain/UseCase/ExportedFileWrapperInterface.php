<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

interface ExportedFileWrapperInterface
{
    public function name(): ?string;

    public function content(): string;
}
