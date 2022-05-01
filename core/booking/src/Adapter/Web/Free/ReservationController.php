<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Free;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Adapter\Web\Free\Form\Dto\BookDto;
use Booking\Adapter\Web\Free\Form\Dto\ReservationFormModel;
use Booking\Adapter\Web\Free\Form\ReservationType;
use Booking\Application\Domain\Model\Book;
use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation", name="app_reservation", methods={"GET","POST"})
 */
class ReservationController extends AbstractController
{
    public function __invoke(Request $request, BookingMailer $bookingMailer, ReservationRepositoryInterface $repository): Response
    {
        // original call
        $form = $this->createForm(ReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ReservationFormModel $formData */
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

            // add book to reservation
            /** @var BookDto $formBook */
            foreach ($formData->books as $formBook) {
                $book = new Book();
                $book->setIsbn($formBook->getIsbn());
                $book->setTitle($formBook->getTitle());
                $book->setAuthor($formBook->getAuthor());
                $book->setVolume($formBook->getVolume());

                $reservation->addBook($book);
            }

            try {
                $repository->save($reservation);
            } catch (\Throwable $exception) {
                throw $exception;
                //throw new \RuntimeException('Errore nel salvataggio dei dati');
            }

            $this->sendEmailToClient($bookingMailer, $formData);

            $this->sendEmailToBackoffice($bookingMailer, $formData);

            $this->addFlash('success', 'Prenotazine avvenuta con successo.');

            return $this->redirectToRoute('app_reservation_result');
        }

        return $this->render('@booking/reservation-page.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function mapPersonDataToReservationConfirmationEmail(ReservationFormModel $formData): array
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

    private function mapBookDataToReservationConfirmationEmail(ReservationFormModel $formData): array
    {
        return $formData->books;
    }

    /**
     * @param BookingMailer $bookingMailer
     * @param ReservationFormModel $formData
     * @return void
     */
    private function sendEmailToClient(BookingMailer $bookingMailer, ReservationFormModel $formData): void
    {
        $bookingMailer->notifyReservationConfirmationEmailToClient(
            $formData->person->getEmail(),
            $this->mapPersonDataToReservationConfirmationEmail($formData),
            $this->mapBookDataToReservationConfirmationEmail($formData),
            $formData->otherInfo
        );
    }

    /**
     * @param BookingMailer $bookingMailer
     * @param ReservationFormModel $formData
     * @return void
     */
    private function sendEmailToBackoffice(BookingMailer $bookingMailer, ReservationFormModel $formData): void
    {
        $bookingMailer->notifyNewReservationToBackoffice(
            $this->mapPersonDataToReservationConfirmationEmail($formData),
            $this->mapBookDataToReservationConfirmationEmail($formData),
            [],
            $formData->otherInfo
        );
    }
}
