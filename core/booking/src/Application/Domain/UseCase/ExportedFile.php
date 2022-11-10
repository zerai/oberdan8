<?php declare(strict_types=1);

namespace Booking\Application\Domain\UseCase;

class ExportedFile implements ExportedFileWrapperInterface
{
    private string $name;

    private string $content;

    public function __construct(string $name, string $content)
    {
        $this->name = $name;
        $this->content = $content;
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
