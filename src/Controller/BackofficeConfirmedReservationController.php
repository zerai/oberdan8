<?php declare(strict_types=1);

namespace App\Controller;

use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/prenotazioni/confermate")
 */
class BackofficeConfirmedReservationController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_reservation_confirmed_index", methods={"GET"})
     */
    public function index(ReservationRepositoryInterface $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');

        $queryBuilder = $repository->findWithQueryBuilderAllConfirmedOrderByOldest($q);

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), //$query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            50 /*limit per page*/
        );

        return $this->render('backoffice/reservation/confirmed/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/scadute", name="backoffice_reservation_confirmed_expired_index", methods={"GET"})
     */
    public function expired(ReservationRepositoryInterface $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $q = $request->query->get('q');

//        $queryBuilder = $repository->findWithQueryBuilderAllConfirmedAndExpiredOrderByOldest($q);
//
//        $pagination = $paginator->paginate(
//            $queryBuilder->getQuery(), //$query, /* query NOT result */
//            $request->query->getInt('page', 1), /*page number*/
//            50 /*limit per page*/
//        );

        // trova tutti i confermati
        $queryBuilder = $repository->findWithQueryBuilderAllConfirmedOrderByOldest($q);

        $confirmedReservation = $queryBuilder->getQuery()->getResult();

        // Filtra se scaduti

        $expiredReservation = [];
        /** @var Reservation $reservation */
        foreach ($confirmedReservation as $reservation) {
            if ($reservation->getSaleDetail()->getConfirmationStatus()->isExpired()) {
                $expiredReservation[] = $reservation;
            }
        }

        //passa array al paginator

        $pagination = $paginator->paginate(
            //$queryBuilder->getQuery(), //$query, /* query NOT result */
            $expiredReservation,
            $request->query->getInt('page', 1), /*page number*/
            50 /*limit per page*/
        );

        return $this->render('backoffice/reservation/confirmed/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
