<?php declare(strict_types=1);

namespace Booking\Adapter\Persistance;

use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository implements ReservationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $reservation): void
    {
        $this->getEntityManager()->persist($reservation);
        $this->getEntityManager()->flush();
    }

    public function delete(Reservation $reservation): void
    {
        $this->getEntityManager()->remove($reservation);
        $this->getEntityManager()->flush();
    }

    public function withId(UuidInterface $reservationId): Reservation
    {
        if (null === $reservation = $this->findOneBy([
            'id' => $reservationId,
        ])) {
            throw new \RuntimeException();
        }

        return $reservation;
    }

    #
    #   READ SIDE
    #

    /**
     * @return int|mixed|string
     */
    public function findAllForBackoffice()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.registrationDate', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string|null $term
     * @param string|null $status
     * @return QueryBuilder
     */
    public function getWithSearchQueryBuilder(?string $term, ?string $status): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.saleDetail', 's')
            ->addSelect('s');

        if (\is_string($term)) {
            $qb->andWhere('r.firstName LIKE :term OR r.LastName LIKE :term OR r.city LIKE :term OR s.GeneralNotes LIKE :term OR s.ReservationPackageId LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        if (\is_string($status)) {
            $qb->andWhere(' s.status LIKE :status ')
                ->setParameter('status', '%' . $status . '%')
            ;
        }
        return $qb
            ->orderBy('r.registrationDate', 'DESC')
            ;
    }

    /**
     * @retrun array<array-key, Reservation>
     */
    public function findAllNewArrivalOrderByNewest(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'NewArrival')
            ->orderBy('r.registrationDate', 'DESC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @retrun array<array-key, Reservation>
     */
    public function findAllConfirmedOrderByOldest(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'Confirmed')
            ->orderBy('r.registrationDate', 'ASC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string|null $term
     * @return QueryBuilder
     */
    public function findWithQueryBuilderAllConfirmedOrderByOldest(?string $term): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.saleDetail', 's')
            ->addSelect('s')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'Confirmed')
            ;

        if (\is_string($term)) {
            $qb->andWhere('r.firstName LIKE :term OR r.LastName LIKE :term OR r.city LIKE :term OR s.GeneralNotes LIKE :term OR s.ReservationPackageId LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb->orderBy('r.registrationDate', 'ASC');
    }

    /**
     * @param string|null $term
     * @return QueryBuilder
     */
    public function findWithQueryBuilderAllConfirmedAndExpiredOrderByOldest(?string $term): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.saleDetail', 's')
            ->addSelect('s')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'Confirmed')
        ;

        if (\is_string($term)) {
            $qb->andWhere('r.firstName LIKE :term OR r.LastName LIKE :term OR r.city LIKE :term OR s.GeneralNotes LIKE :term OR s.ReservationPackageId LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        $todayMinus7 = (new \DateTimeImmutable("today"))->modify('- 7days'); //dd($todayMinus7);
        $qb->andWhere('s.pvtConfirmedAt < :todayMinus7 AND s.pvtExtensionTime = false')
            //->setParameter('todayMinus7', $todayMinus7->format('Y-m-d'));
            ->setParameter('todayMinus7', $todayMinus7, Types::DATETIME_IMMUTABLE);

        $todayMinus14 = (new \DateTimeImmutable("today"))->modify('- 14days'); //dd($todayPlus7);
        $qb->orWhere('s.pvtConfirmedAt < :todayMinus14 AND s.pvtExtensionTime = true')
            ->setParameter('todayMinus14', $todayMinus14, Types::DATETIME_IMMUTABLE);

        return $qb->orderBy('r.registrationDate', 'ASC');
    }

    public function countWithStatusConfirmedAndExpired(): int
    {
        $expired = [];

        $qb = $this->findWithQueryBuilderAllConfirmedOrderByOldest('');

        $result = $qb->getQuery()->getResult();

        /** @var Reservation $reservation */
        foreach ($result as $reservation) {
            if ($reservation->getSaleDetail()->getConfirmationStatus()->isExpired()) {
                $expired[] = $reservation;
            }
        }

        return (int) \count($expired);
    }

    //
    //  Stats query
    //

    public function countWithStatusNewArrival(): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'NewArrival')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function countWithStatusInProgress(): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'InProgress')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function countWithStatusPending(): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'Pending')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function countWithStatusRejected(): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'Rejected')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function countWithStatusConfirmed(): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'Confirmed')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function countWithStatusSale(): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'Sale')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function countWithStatusPickedUp(): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'PickedUp')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function countWithStatusBlacklist(): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->leftJoin('r.saleDetail', 's')
            ->andWhere('s.status = :val')
            ->setParameter('val', 'Blacklist')
        ;

        $query = $qb->getQuery();

        return (int) $query->getSingleScalarResult();
    }
}
