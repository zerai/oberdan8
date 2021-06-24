<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Infrastructure\Framework\Form\AdozioniReservationType;
use Booking\Infrastructure\Framework\Form\Dto\AdozioniReservationFormModel;
use Booking\Infrastructure\Framework\Form\Service\AdozioniUploaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdozioniReservationController extends AbstractController
{
    public function __invoke(Request $request, AdozioniUploaderInterface $uploader, BookingMailer $bookingMailer): Response
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
                    // original call
                    //$newFilename = $uploader->uploadAdozioniFile($adozioniFile);

                    $newFilename = $uploader->uploadAdozioniFileAndReturnFile($adozioniFile);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw $e;
                }
            }
            ############################ fine single file upload

            /** @var AdozioniReservationFormModel $formData */
            $formData = $form->getData();

            // TODO APPLICATION lOGIC
            // 1 PERSIST RESERVATION
            // 2 SEND EMAILS TO CLIENT
            // 3 SEND EMAILS TO BACKOFFICE

            // send email to client
            $bookingMailer->notifyAdozioniReservationConfirmationEmailToClient(
                $formData->person->getEmail(),
                $this->mapPersonDataToReservationConfirmationEmail($formData),
                [$adozioniFile->getClientOriginalName()],
                $formData->otherInfo
            );

            // send email to backoffice
            $bookingMailer->notifyNewAdozioniReservationToBackoffice(
                $this->mapPersonDataToReservationConfirmationEmail($formData),
                //[$adozioniFile->getClientOriginalName()],
                [$newFilename],
                [],
                $formData->otherInfo
            );

            $this->addFlash('success', 'Prenotazine avvenuta con successo.');

            return $this->redirectToRoute('reservation_result');
        }

        return $this->render('@booking/reservation-adozioni-page.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function mapPersonDataToReservationConfirmationEmail(AdozioniReservationFormModel $formData): array
    {
        return [
            'firstName' => $formData->person->getFirstName(),
            'lastName' => $formData->person->getLastName(),
            'contact_email' => $formData->person->getEmail(),
            'phone' => $formData->person->getPhone(),
            'city' => $formData->person->getCity(),
            'classe' => $formData->classe,
        ];
    }
}
