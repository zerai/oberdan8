<?php declare(strict_types=1);

namespace App\Controller;

use Booking\Adapter\Persistance\ReservationRepository;
use Booking\Application\Domain\Model\Reservation;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/stats")
 */
class BackofficeReservationStatsController extends AbstractController
{
    /**
     * @Route("/reservation-status-overview", name="backoffice_stats_reservation_overview", methods={"GET"})
     */
    public function index(ReservationRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $confirmedStatus = $repository->countWithStatusConfirmed();

        $newArrivalStatus = $repository->countWithStatusNewArrival();

        $pendingStatus = $repository->countWithStatusPending();

        $inprogressStatus = $repository->countWithStatusInProgress();

        $rejectedStatus = $repository->countWithStatusRejected();

        $saleStatus = $repository->countWithStatusSale();

        $pickedUpStatus = $repository->countWithStatusPickedUp();

        return $this->render('backoffice/reservation/_badge_status_navbar.html.twig', [
            'status_confirmed' => $confirmedStatus,
            'status_newarrival' => $newArrivalStatus,
            'status_pending' => $pendingStatus,
            'status_inprogress' => $inprogressStatus,
            'status_rejected' => $rejectedStatus,
            'status_sale' => $saleStatus,
            'status_pickedup' => $pickedUpStatus,
        ]);
    }
}
