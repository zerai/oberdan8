<?php declare(strict_types=1);

namespace Booking\Infrastructure\Exporter;

use Booking\Application\Domain\UseCase\ExportedFileWrapperInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExportDataHttpResponseFactory
{
    public static function createFromExportedFile(ExportedFileWrapperInterface $file, int $status = 200, int $maxage = 0, int $smaxage = 0): Response
    {
        $response = new Response($file->content(), $status);

        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $contentDisposition = (string) $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $file->name());
        $response->headers->set('Content-Disposition', $contentDisposition);

        $response->setMaxAge($maxage);
        $response->setSharedMaxAge($smaxage);
        $response->setPublic();

        return $response;
    }
}
