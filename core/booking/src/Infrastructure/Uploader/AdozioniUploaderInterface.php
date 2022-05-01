<?php declare(strict_types=1);


namespace Booking\Infrastructure\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface AdozioniUploaderInterface
{
    public function uploadAdozioniFile(UploadedFile $uploadedAdozioniFile): string;
}
