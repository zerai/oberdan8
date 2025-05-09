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
 * @Route("/admin/coupon/prenotazioni")
 */
class BackofficeCouponCodeReservationController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_reservation_coupon_index", methods={"GET"})
     */
    public function index(ReservationRepositoryInterface $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $searchTerms = $request->query->get('q');
        $pageNumber = $request->query->getInt('page', 1);
        $limitResults = 50;

        $queryBuilder = $repository->findWithQueryBuilderAllWithCouponCodeOrderByNewest($searchTerms);

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $pageNumber,
            $limitResults
        );

        return $this->render('backoffice/reservation/with_coupon/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
