<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Infrastructure\Framework\Form\AdozioniReservationType;
use Booking\Infrastructure\Framework\Form\Dto\AdozioniReservationFormModel;
use Booking\Infrastructure\Framework\Form\Service\AdozioniUploaderInterface;
use LeanpubBookClub\Application\UpdateSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdozioniController extends AbstractController
{
    public function __invoke(Request $request, AdozioniUploaderInterface $uploader): Response
    {
        $form = $this->createForm(AdozioniReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            ############################ inizio single file upload
            /** @var UploadedFile $adozioniFile */
            $adozioniFile = $form->get('adozioni')->getData();

            // and is not empty
            if ($adozioniFile) {
                try {
                    $newFilename = $uploader->uploadAdozioniFile($adozioniFile);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw $e;
                }
            }
            ############################ fine single file upload

            ############################ elaborazione form
            #
            #
            #
            /** @var AdozioniReservationFormModel $userModel */
            $adozioniReservationFormModel = $form->getData();

            ############################ Execute Application/domain logic here
            #
            #   get file data ($newFilename)
            #   get form data
            #   log in file
            #   send mail to backoffice
            #
//            $this->application->updateSession(
//                new UpdateSession($sessionId, $formData['description'], $formData['urlForCall'])
//            );
            ############################ Fine Execute Application/domain logic here

            ## Send flash message "La tua prenotazione è stata ricevuta."

            return $this->redirectToRoute('home_oberdan');
        }

        return $this->render('@booking/Static/reservation-adozioni.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
