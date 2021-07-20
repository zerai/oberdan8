<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
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
    public function __invoke(Request $request, AdozioniUploaderInterface $uploader, BookingMailer $bookingMailer, ReservationRepositoryInterface $repository): Response
    {
        $form = $this->createForm(AdozioniReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailAttachments = [];
            ############################ inizio single file upload
            /** @var UploadedFile $adozioniFile */
            $adozioniFile = $form->get('adozioni')->getData();

            // and is not empty
            // TODO static analysis Only booleans are allowed in an if condition, Symfony\Component\HttpFoundation\File\UploadedFile given.
            if ($adozioniFile) {
                try {
                    // original call
                    //$newFilename = $uploader->uploadAdozioniFile($adozioniFile);

                    $newFilename = $uploader->uploadAdozioniFileAndReturnFile($adozioniFile);
                    $mailAttachments[] = $newFilename;
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw $e;
                }
            }
            ############################ fine single file upload

            ############################ inizio second file upload
            /** @var UploadedFile $secondAdozioniFile */
            $secondAdozioniFile = $form->get('adozioni2')->getData();

            // and is not empty
            // TODO static analysis Only booleans are allowed in an if condition, Symfony\Component\HttpFoundation\File\UploadedFile given.
            if ($secondAdozioniFile) {
                try {
                    // original call
                    //$newFilename = $uploader->uploadAdozioniFile($adozioniFile);

                    $secondFilename = $uploader->uploadAdozioniFileAndReturnFile($secondAdozioniFile);
                    $mailAttachments[] = $secondFilename;
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw $e;
                }
            }
            ############################ fine second file upload

            ############################ inizio third file upload
            /** @var UploadedFile $thirdAdozioniFile */
            $thirdAdozioniFile = $form->get('adozioni3')->getData();

            // and is not empty
            // TODO static analysis Only booleans are allowed in an if condition, Symfony\Component\HttpFoundation\File\UploadedFile given.
            if ($thirdAdozioniFile) {
                try {
                    // original call
                    //$newFilename = $uploader->uploadAdozioniFile($adozioniFile);

                    $thirdFilename = $uploader->uploadAdozioniFileAndReturnFile($thirdAdozioniFile);
                    $mailAttachments[] = $thirdFilename;
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw $e;
                }
            }
            ############################ fine third file upload

            /** @var AdozioniReservationFormModel $formData */
            $formData = $form->getData();

            // TODO APPLICATION lOGIC
            // 1 PERSIST RESERVATION
            // 2 SEND EMAILS TO CLIENT
            // 3 SEND EMAILS TO BACKOFFICE

            $reservation = new Reservation();
            $reservation->setFirstName($formData->person->getFirstName())
                ->setLastName($formData->person->getLastName())
                ->setEmail($formData->person->getEmail())
                ->setPhone($formData->person->getPhone())
                ->setCity($formData->person->getCity())
                ->setClasse($formData->classe)
                ->setOtherInformation($formData->otherInfo)
                ->setRegistrationDate(
                    new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'))
                );

            // add saleDetail to reservation
            $saleDetail = new ReservationSaleDetail();
            $saleDetail->setStatus(ReservationStatus::NewArrival());
            $reservation->setSaleDetail($saleDetail);

            // add files to reservation

            try {
                $repository->save($reservation);
            } catch (\Throwable $exception) {
                throw $exception;
                //throw new \RuntimeException('Errore nel salvataggio dei dati');
            }

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
                $mailAttachments,
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
