<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin;

use Booking\Adapter\Persistence\ReservationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(ReservationRepository $repository): Response
    {
        $confirmedStatus = $repository->countWithStatusConfirmed();

        $newArrivalStatus = $repository->countWithStatusNewArrival();

        $pendingStatus = $repository->countWithStatusPending();

        $inProgressStatus = $repository->countWithStatusInProgress();

        $rejectedStatus = $repository->countWithStatusRejected();

        $saleStatus = $repository->countWithStatusSale();

        $pickedUpStatus = $repository->countWithStatusPickedUp();

        $expiredStatus = $repository->countWithStatusConfirmedAndExpired();

        $shippedStatus = $repository->countWithStatusShipped();

        return $this->render('backoffice/reservation/_badge_status_navbar.html.twig', [
            'status_confirmed' => $confirmedStatus,
            'status_newarrival' => $newArrivalStatus,
            'status_pending' => $pendingStatus,
            'status_inprogress' => $inProgressStatus,
            'status_rejected' => $rejectedStatus,
            'status_sale' => $saleStatus,
            'status_pickedup' => $pickedUpStatus,
            'status_expired' => $expiredStatus,
            'status_shipped' => $shippedStatus,
        ]);
    }
}
