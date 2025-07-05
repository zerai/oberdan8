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
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/reservation', name: 'app_reservation', methods: ['GET', 'POST'])]
class ReservationController extends AbstractController
{
    public function __construct(
        private readonly RateLimiterFactory $reservationFormsLimiter,
    ) {
    }

    public function __invoke(Request $request, BookingMailer $bookingMailer, ReservationRepositoryInterface $repository): Response
    {
        // original call
        $form = $this->createForm(ReservationType::class);

        try {
            if ('POST' === $request->getMethod()) {
                $this->verifyRateLimiterThrottling($request);
            }
        } catch (TooManyRequestsHttpException) {
            $errorMessage = 'Hai superato il numero massimo di invii consentiti. Riprova tra 10 minuti';
            $form->addError(new FormError($errorMessage));
            return $this->render('@booking/reservation-page.html.twig', [
                'form' => $form->createView(),
            ]);
        }


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
                ->setCoupondCode($formData->coupondCode)
                ->setRegistrationDate(
                    new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'))
                );

            // add saleDetail to reservation
            $saleDetail = new ReservationSaleDetail();
            $saleDetail->setStatus(ReservationStatus::newArrival());
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
            } catch (Throwable $exception) {
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
            $formData->otherInfo,
            $formData->coupondCode
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
            $formData->otherInfo,
            $formData->coupondCode
        );
    }

    private function verifyRateLimiterThrottling(Request $request): void
    {
        // create a limiter based on a unique identifier of the client
        // (e.g. the client's IP address, a username/email, an API key, etc.)
        $limiter = $this->reservationFormsLimiter->create($request->getClientIp());
        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        // to reset the counter
        //$limiter->reset();
    }
}
