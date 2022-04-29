<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\MailOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MailOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailOut[]    findAll()
 * @method MailOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailOut::class);
    }

    // /**
    //  * @return MailOut[] Returns an array of MailOut objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MailOut
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
