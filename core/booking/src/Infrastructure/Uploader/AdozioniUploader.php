<?php declare(strict_types=1);


namespace Booking\Infrastructure\Uploader;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdozioniUploader implements AdozioniUploaderInterface
{
    public function __construct(
        private string $adozioniUploadsDirectory
    ) {
    }

    public function uploadAdozioniFile(UploadedFile $uploadedAdozioniFile): string
    {
        $destination = $this->adozioniUploadsDirectory;

        $originalFilename = pathinfo($uploadedAdozioniFile->getClientOriginalName(), PATHINFO_FILENAME);

        // this is needed to safely include the file name as part of the URL
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedAdozioniFile->guessExtension();

        $uploadedAdozioniFile->move(
            $destination,
            $newFilename
        );
        return $newFilename;
    }

    public function uploadAdozioniFileAndReturnFile(UploadedFile $uploadedAdozioniFile): File
    {
        $destination = $this->adozioniUploadsDirectory;
        $originalFilename = pathinfo($uploadedAdozioniFile->getClientOriginalName(), PATHINFO_FILENAME);

        // this is needed to safely include the file name as part of the URL
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedAdozioniFile->guessExtension();
        return $uploadedAdozioniFile->move(
            $destination,
            $newFilename
        );
    }
}
