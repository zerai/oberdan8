<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\InfoBox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InfoBox|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoBox|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoBox[]    findAll()
 * @method InfoBox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoBoxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoBox::class);
    }

    public function findDefaultInfoBox(bool $isDefaultBox = true): ?InfoBox
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.defaultBox = :value')
            ->setParameter('value', $isDefaultBox)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
