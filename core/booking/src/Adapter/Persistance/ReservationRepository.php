<?php declare(strict_types=1);

namespace Booking\Adapter\Persistance;

use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        $this->_em->persist($reservation);
        $this->_em->flush();
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function delete(Reservation $reservation): void
    {
        $this->_em->remove($reservation);
        $this->_em->flush();
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
            //->andWhere('r.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy('r.registrationDate', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string|null $term
     * @return QueryBuilder
     */
    public function getWithSearchQueryBuilder(?string $term): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r')
            //->innerJoin('c.article', 'a')
            ->innerJoin('r.saleDetail', 's')
            ->addSelect('s');

        // TODO fix static analysis (Only booleans are allowed in an if condition, string|null given. )
        if ($term) {
            $qb->andWhere('r.firstName LIKE :term OR r.LastName LIKE :term OR r.city LIKE :term OR s.GeneralNotes LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb
            ->orderBy('r.registrationDate', 'DESC')
            ;
    }

    /**
     * @return Reservation[]
     */
    public function findAllNewArrivalOrderByNewest()
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
     * @return Reservation[]
     */
    public function findAllConfirmedOrderByOldest()
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

            //->setMaxResults(1000)
            //->getQuery()
            //->getResult()
            ;

        // TODO fix static analysis (Only booleans are allowed in an if condition, string|null given. )
        if ($term) {
            $qb->andWhere('r.firstName LIKE :term OR r.LastName LIKE :term OR r.city LIKE :term OR s.GeneralNotes LIKE :term')
                ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $qb->orderBy('r.registrationDate', 'ASC');
    }
}
