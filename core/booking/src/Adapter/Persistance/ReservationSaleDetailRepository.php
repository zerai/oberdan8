<?php declare(strict_types=1);

namespace Booking\Adapter\Persistance;

use Booking\Application\Domain\Model\ReservationSaleDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationSaleDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationSaleDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationSaleDetail[]    findAll()
 * @method ReservationSaleDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationSaleDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationSaleDetail::class);
    }

    // /**
    //  * @return ReservationSaleDetail[] Returns an array of ReservationSaleDetail objects
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
    public function findOneBySomeField($value): ?ReservationSaleDetail
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
