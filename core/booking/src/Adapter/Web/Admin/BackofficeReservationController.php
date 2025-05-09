<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Adapter\Persistence\BookRepository;
use Booking\Adapter\Web\Admin\Form\BackofficeReservationType;
use Booking\Adapter\Web\Admin\Form\Dto\ClientDto;
use Booking\Adapter\Web\Admin\Form\Model\BackofficeReservationFormModel;
use Booking\Adapter\Web\Free\Form\Dto\BookDto;
use Booking\Application\Domain\Model\Book;
use Booking\Application\Domain\Model\ConfirmationStatus\ExtensionTime;
use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/admin/prenotazioni")
 */
class BackofficeReservationController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_reservation_index", methods={"GET"})
     */
    public function index(ReservationRepositoryInterface $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');
        $status = $request->query->get('status');
        $pageNumber = $request->query->getInt('page', 1);
        $limitResults = 15;

        $queryBuilder = $repository->getWithSearchQueryBuilder($q, $status);

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $pageNumber,
            $limitResults,
        );

        return $this->render('backoffice/reservation/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="backoffice_reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request, ReservationRepositoryInterface $repository): Response
    {
        $reservationFormModel = new BackofficeReservationFormModel();
        $form = $this->createForm(BackofficeReservationType::class, $reservationFormModel, [
            'include_packageId' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var BackofficeReservationFormModel $formData */
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
                //Todo remove other info
                //->setOtherInformation($formData->otherInfo)
                ->setRegistrationDate(
                    new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'))
                );

            // add saleDetail to reservation
            $saleDetail = new ReservationSaleDetail();
            $saleDetail->setStatus(ReservationStatus::newArrival());

            if (isset($formData->packageId)) {
                $saleDetail->setReservationPackageId($formData->packageId);
            }

            $saleDetail->setGeneralNotes($formData->generalNotes);
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

            return $this->redirectToRoute('backoffice_reservation_index');
        }

        return $this->render('backoffice/reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('backoffice/reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    private function mapReservationToFormModel(Reservation $reservation): BackofficeReservationFormModel
    {
        //create clientDto
        $clientDto = new ClientDto();
        $clientDto->setLastName($reservation->getLastName());
        $clientDto->setFirstName($reservation->getFirstName());
        $clientDto->setEmail($reservation->getEmail());
        $clientDto->setPhone($reservation->getPhone());
        $clientDto->setCity($reservation->getCity());

        $formModel = new BackofficeReservationFormModel();
        $formModel->person = $clientDto;
        $formModel->classe = $reservation->getClasse();
        $formModel->status = $reservation->getSaleDetail()->getStatus()->toString();
        $formModel->packageId = $reservation->getSaleDetail()->getReservationPackageId();
        $formModel->generalNotes = $reservation->getSaleDetail()->getGeneralNotes() ?? '';

        return $formModel;
    }

    /**
     * @Route("/{id}/edit", name="backoffice_reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation, BookingMailer $bookingMailer, EntityManagerInterface $entityManager): Response
    {
        $formModel = $this->mapReservationToFormModel($reservation);

        $form = $this->createForm(BackofficeReservationType::class, $formModel, [
            'include_reservation_status' => true,
            'include_packageId' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * add try/catch block
             */
            $reservation->setLastName($form->get('person')->getData()->getLastName());
            $reservation->setFirstName($form->get('person')->getData()->getFirstName());
            $reservation->setEmail($form->get('person')->getData()->getEmail());
            $reservation->setPhone($form->get('person')->getData()->getPhone());
            $reservation->setCity($form->get('person')->getData()->getCity());
            $reservation->setClasse($form->get('classe')->getData());

            /**
             * update books
             */
            /** @var BookDto $formBook */
            foreach ($form->get('books')->getData() as $formBook) {
                $book = new Book();
                $book->setIsbn($formBook->getIsbn());
                $book->setTitle($formBook->getTitle());
                $book->setAuthor($formBook->getAuthor());
                $book->setVolume($formBook->getVolume());

                $reservation->addBook($book);
            }

            /**
             * Aggiorna lo ReservationStatus solo se differente da quello attuale
             * altrimenti il ConfirmedStatus viene ricalcolato
             */
            if ($reservation->getSaleDetail()->getStatus()->name() !== $form->get('status')->getData()) {
                $reservation->getSaleDetail()->setStatus(
                    ReservationStatus::fromName($form->get('status')->getData())
                );
            }
            $reservation->getSaleDetail()->setReservationPackageId($form->get('packageId')->getData());
            $reservation->getSaleDetail()->setGeneralNotes($form->get('generalNotes')->getData());

            $entityManager->persist($reservation);
            $entityManager->flush();

            if ($reservation->getSaleDetail()->getStatus()->name() === 'PickedUp') {
                if ($reservation->getEmail() !== null && $reservation->getEmail() !== '') {
                    /**
                     * Send in async with retry
                     */
                    $bookingMailer->notifyReservationThanksEmailToClient($reservation->getEmail(), $reservation->getId()->toString());
                    $this->addFlash('info', 'La mail di ringraziamento è stata inviata all\' utente.');
                } else {
                    $this->addFlash('info', 'Impossibile invio della mail di ringraziamento, questa prenotazione non ha un indirizzo email.');
                }
            }

            $this->addFlash('success', 'Prenotazione modificata.');

            return $this->redirectToRoute('backoffice_reservation_show', [
                'id' => $reservation->getId(),
            ]);
        }

        return $this->render('backoffice/reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId()->toString(), (string) $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'La prenotazione è stata eliminata.');
        }

        return $this->redirectToRoute('backoffice_reservation_index');
    }

    /**
     * @Route("/{id}/delete-book/{book_id}", name="backoffice_reservation_delete_book", methods={"POST"})
     */
    public function deleteBookFromReservation(Request $request, Reservation $reservation, BookRepository $bookRepository, LoggerInterface $logger, EntityManagerInterface $entityManager): Response
    {
        $bookId = $request->request->get('book_id');

        // TODO Migliore messaggio nel logger ?? Libro già rimosso? o altro
        if ($bookId === null) {
            //TODO throw exception, message to user
            $logger->alert('Impossibile rimuvere libro da prenotazione.');
        }

        if ($this->isCsrfTokenValid('delete-book' . (string) $bookId . $reservation->getId()->toString(), (string) $request->request->get('_token'))) {
            $book = $bookRepository->findOneBy([
                'id' => Uuid::fromString((string) $bookId),
            ]);

            if ($book !== null) {
                $reservation->removeBook($book);

                $entityManager->persist($reservation);
                $entityManager->flush();

                $this->addFlash('success', 'La prenotazione è stata modificata.');
            } else {
                //TODO throw exception, message to user
                $logger->alert('Impossibile rimuvere libro da prenotazione.');
            }
        }

        return $this->redirectToRoute('backoffice_reservation_edit', [
            'id' => $reservation->getId()->toString(),
        ]);
    }

    /**
     * Rimuovere dopo il deploy (i test passano anche senza...)
     * durante l'update viene usato $bookingMailer->notifyReservationThanksEmailToClient()
     * questo metodo è inutile
     */
    //    /**
    //     * @Route("/{id}", name="backoffice_reservation_send_tanks_mail", methods={"POST"})
    //     */
    //    public function sendThanksMail(Request $request, Reservation $reservation): Response
    //    {
    //        if ($this->isCsrfTokenValid('send_tanks_mail' . $reservation->getId()->toString(), (string) $request->request->get('_token'))) {
    //            $this->getDoctrine()->getManager();
    //
    //            $this->addFlash('success', 'E\' stata inviata la mail di ringraziamento .');
    //        } else {
    //            $this->addFlash('danger', 'La mail di ringraziamento non è stata inviata.');
    //        }
    //
    //        return $this->redirectToRoute('backoffice_reservation_index');
    //    }

    /**
     * @Route("/{id}/add-extension-time", name="backoffice_reservation_add_extension_time", methods={"POST"})
     */
    public function addExtensionTimeToConfirmation(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('add-extension-time' . $reservation->getId()->toString(), (string) $request->request->get('_token'))) {
            //Todo before check if status is Confirmed and confirmationStatus not null
            $newConfirmationStatusWithExtensionTime = $reservation->getSaleDetail()->getConfirmationStatus()->withExtensionTime(ExtensionTime::true());

            $reservation->getSaleDetail()->setConfirmationStatus($newConfirmationStatusWithExtensionTime);

            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'E\' stato aggiunto un estensione di tempo (7 giorni) per il ritiro.');
        }

        return $this->redirectToRoute('backoffice_reservation_show', [
            'id' => $reservation->getId(),
        ]);
    }
}
