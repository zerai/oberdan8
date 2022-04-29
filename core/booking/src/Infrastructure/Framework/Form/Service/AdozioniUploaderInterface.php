<?php declare(strict_types=1);


namespace Booking\Infrastructure\Framework\Form\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface AdozioniUploaderInterface
{
    public function uploadAdozioniFile(UploadedFile $uploadedAdozioniFile): string;
}
