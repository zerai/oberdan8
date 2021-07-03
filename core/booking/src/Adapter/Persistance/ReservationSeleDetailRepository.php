<?php declare(strict_types=1);

namespace Booking\Adapter\Persistance;

use Booking\Application\Domain\Model\ReservationSeleDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationSeleDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationSeleDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationSeleDetail[]    findAll()
 * @method ReservationSeleDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationSeleDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationSeleDetail::class);
    }

    // /**
    //  * @return ReservationSeleDetail[] Returns an array of ReservationSeleDetail objects
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
    public function findOneBySomeField($value): ?ReservationSeleDetail
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
