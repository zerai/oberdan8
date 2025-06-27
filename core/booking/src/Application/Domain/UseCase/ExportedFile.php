<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

class ExportedFile implements ExportedFileWrapperInterface
{
    public function __construct(
        private string $name,
        private string $content
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function content(): string
    {
        return $this->content;
    }
}
