<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin;

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
        $pageNumber = $request->query->getInt('page', 1);
        $limitResult = 50;

        $queryBuilder = $repository->findWithQueryBuilderAllConfirmedOrderByOldest($q);

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $pageNumber,
            $limitResult,
        );

        return $this->render('backoffice/reservation/confirmed/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/scadute", name="backoffice_reservation_expired_index", methods={"GET"})
     */
    public function expired(ReservationRepositoryInterface $repository, Request $request, PaginatorInterface $paginator): Response
    {
        /**
         * TODO: add func. tests
         */
        $q = $request->query->get('q');
        $pageNumber = $request->query->getInt('page', 1);
        $limitResult = 50;

        $confirmedReservation = $repository->findWithQueryBuilderAllConfirmedOrderByOldest($q)->getQuery()->getResult();

        /**
         * Filtra i confermati e ricava gli scaduti
         * TODO: usare array filter or inline function
         */
        $expiredReservation = [];
        /** @var Reservation $reservation */
        foreach ($confirmedReservation as $reservation) {
            if ($reservation->getSaleDetail()->getConfirmationStatus()->isExpired()) {
                $expiredReservation[] = $reservation;
            }
        }

        $pagination = $paginator->paginate(
            $expiredReservation,
            $pageNumber,
            $limitResult,
        );

        return $this->render('backoffice/reservation/expired/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
