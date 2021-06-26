<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Application\Domain\Model\Book;
use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Booking\Infrastructure\Framework\Form\Dto\BookDto;
use Booking\Infrastructure\Framework\Form\Dto\ReservationFormModel;
use Booking\Infrastructure\Framework\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
                    new \DateTimeImmutable("now")
                );
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
                throw new \RuntimeException('Errore nel salvataggio dei dati');
            }

            // send email to client
            $bookingMailer->notifyReservationConfirmationEmailToClient(
                $formData->person->getEmail(),
                $this->mapPersonDataToReservationConfirmationEmail($formData),
                $this->mapBookDataToReservationConfirmationEmail($formData),
                $formData->otherInfo
            );

            // send email to backoffice
            //$emailForBackoffice = $this->createRiepilogoEmailForBackoffice($formData);
            $bookingMailer->notifyNewReservationToBackoffice(
                $this->mapPersonDataToReservationConfirmationEmail($formData),
                $this->mapBookDataToReservationConfirmationEmail($formData),
                [],
                $formData->otherInfo
            );

            $this->addFlash('success', 'Prenotazine avvenuta con successo.');

            return $this->redirectToRoute('reservation_result');
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
}
